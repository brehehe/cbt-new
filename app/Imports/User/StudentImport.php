<?php

namespace App\Imports\User;

use App\Helpers\RoleHelper;
use App\Models\Company\Company;
use App\Models\Master\Exam\ExamRoom;
use App\Models\Master\Exam\ExamSession;
use App\Models\Study\Study;
use App\Models\User;
use App\Models\User\UserDetail;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class StudentImport implements ToCollection, WithHeadingRow
{
    protected ?string $typeStudy;

    protected ?string $companyId;

    public int $successCount = 0;

    public int $errorCount = 0;

    public array $errors = [];

    public function __construct(?string $typeStudy = null, ?string $companyId = null)
    {
        $this->typeStudy = $typeStudy;
        $this->companyId = $companyId;
    }

    public function collection(Collection $rows)
    {
        try {
            $currentCompanyId = $this->companyId ?? (Auth::check() ? Auth::user()->company_id : null);
            if (empty($currentCompanyId)) {
                throw new Exception('Company ID is required for student import.');
            }

            $this->successCount = 0;
            $this->errorCount = 0;
            $this->errors = [];

            foreach ($rows as $index => $row) {
                try {
                    DB::beginTransaction();

                    $resolvedTypeStudy = $this->typeStudy ?? (! empty($row['type_study']) ? $row['type_study'] : 'general');

                    // Validate required fields
                    if (empty($row['name']) || empty($row['email'])) {
                        throw new Exception('Row '.($index + 2).': Name and Email are required');
                    }

                    if ($resolvedTypeStudy === 'general' && empty($row['username'])) {
                        throw new Exception('Row '.($index + 2).': Username is required for general');
                    }

                    $rowNim = ! empty($row['nim']) ? trim($row['nim']) : null;

                    // Check if user already exists by email
                    $existingUser = User::where('email', $row['email'])
                        ->where('company_id', $currentCompanyId)
                        ->where('type_user', 'employee')
                        ->first();

                    if ($existingUser) {
                        throw new Exception('Row '.($index + 2).': User with email already exists');
                    }

                    // Check if user already exists by NIM (if NIM is provided)
                    if ($rowNim) {
                        // Check users table
                        $existingUserByNim = User::where('nim', $rowNim)
                            ->where('company_id', $currentCompanyId)
                            ->where('type_user', 'employee')
                            ->first();

                        if ($existingUserByNim) {
                            throw new Exception('Row '.($index + 2).': User with NIM '.$rowNim.' already exists in users table');
                        }

                        // Check user_details table
                        $existingUserDetailByNim = UserDetail::where('company_id', $currentCompanyId)
                            ->where(function ($q) use ($rowNim) {
                                $q->where('nim', $rowNim)
                                    ->orWhere('student_id', $rowNim);
                            })
                            ->first();

                        if ($existingUserDetailByNim) {
                            throw new Exception('Row '.($index + 2).': User with NIM '.$rowNim.' already exists in user details');
                        }
                    }

                    // Get study_id if program_studi is provided
                    $studyId = null;
                    if (! empty($row['program_studi'])) {
                        $operator = DB::connection()->getDriverName() === 'pgsql' ? 'ilike' : 'like';
                        $study = Study::withoutGlobalScope('user_scope')
                            ->where('company_id', $currentCompanyId)
                            ->where('name', $operator, '%'.$row['program_studi'].'%')
                            ->first();
                        if ($study) {
                            $studyId = $study->id;
                        }
                    }

                    // Default password if not provided
                    $password = ! empty($row['password']) ? $row['password'] : 'password123';
                    $typeStudy = $resolvedTypeStudy;

                    // Create user
                    $user = User::create([
                        'name' => $row['name'],
                        'nim' => $rowNim,
                        'username' => $row['username'] ?? null,
                        'email' => $row['email'],
                        'password' => Hash::make($password),
                        'phone' => $row['phone'] ?? '0',
                        'study_id' => $studyId,
                        'company_id' => $currentCompanyId,
                        'type_user' => 'employee',
                        'type_study' => $typeStudy,
                    ]);

                    // Create user detail
                    $detailData = [
                        'user_id' => $user->id,
                        'company_id' => $currentCompanyId,
                        'student_id' => $rowNim,
                        'nim' => $rowNim,
                        'address' => $row['address'] ?? null,
                        'student_faculty' => $row['faculty'] ?? null,
                        'student_department' => $row['department'] ?? null,
                        'student_semester' => $row['semester'] ?? null,
                        'student_status' => $row['student_status'] ?? 'active',
                    ];

                    // Handle identity_number encryption if provided
                    if (! empty($row['identity_number'])) {
                        try {
                            $detailData['identity_number'] = Crypt::encryptString($row['identity_number']);
                        } catch (Exception $e) {
                            $detailData['identity_number'] = $row['identity_number'];
                        }
                    }

                    $company = Company::find($currentCompanyId);
                    if ($company && $company->import_student_timetable) {
                        $examSessionId = null;
                        $sessionVal = $row['sesi'] ?? $row['sesi_ujian'] ?? $row['exam_session'] ?? null;
                        if (! empty($sessionVal)) {
                            $sessionName = trim($sessionVal);
                            $session = ExamSession::where('company_id', $currentCompanyId)
                                ->where('name', $sessionName)
                                ->first();
                            if (! $session) {
                                $session = ExamSession::create([
                                    'company_id' => $currentCompanyId,
                                    'name' => $sessionName,
                                    'code' => strtoupper(Str::slug($sessionName)),
                                    'is_active' => true,
                                ]);
                            }
                            $examSessionId = $session->id;
                        }

                        $examRoomId = null;
                        $roomVal = $row['ruang'] ?? $row['ruang_ujian'] ?? $row['exam_room'] ?? null;
                        if (! empty($roomVal)) {
                            $roomName = trim($roomVal);
                            $room = ExamRoom::where('company_id', $currentCompanyId)
                                ->where('name', $roomName)
                                ->first();
                            if (! $room) {
                                $room = ExamRoom::create([
                                    'company_id' => $currentCompanyId,
                                    'name' => $roomName,
                                    'code' => strtoupper(Str::slug($roomName)),
                                ]);
                            }
                            $examRoomId = $room->id;
                        }

                        $examDate = null;
                        $dateVal = $row['tanggal'] ?? $row['tanggal_ujian'] ?? $row['exam_date'] ?? null;
                        if (! empty($dateVal)) {
                            try {
                                if (is_numeric($dateVal)) {
                                    $examDate = Carbon::instance(Date::excelToDateTimeObject($dateVal))->format('Y-m-d');
                                } else {
                                    $dateStr = trim($dateVal);
                                    $normalizedDate = str_replace('-', '/', $dateStr);
                                    if (preg_match('/^\d{1,2}\/\d{1,2}\/\d{2,4}$/', $normalizedDate)) {
                                        $parts = explode('/', $normalizedDate);
                                        if (strlen($parts[2]) === 2) {
                                            $examDate = Carbon::createFromFormat('d/m/y', $normalizedDate)->format('Y-m-d');
                                        } else {
                                            $examDate = Carbon::createFromFormat('d/m/Y', $normalizedDate)->format('Y-m-d');
                                        }
                                    } else {
                                        $examDate = Carbon::parse($dateStr)->format('Y-m-d');
                                    }
                                }
                            } catch (Exception $e) {
                                // silent
                            }
                        }

                        $detailData['exam_session_id'] = $examSessionId;
                        $detailData['exam_room_id'] = $examRoomId;
                        $detailData['exam_date'] = $examDate;
                    }

                    UserDetail::create($detailData);

                    // Assign Student role
                    $isHead = true;
                    $isActive = true;

                    RoleHelper::assignRoleToUserInCompany(
                        $user,
                        'Mahasiswa',
                        $currentCompanyId,
                        null,
                        $isHead,
                        $isActive
                    );

                    DB::commit();
                    $this->successCount++;
                } catch (Exception $e) {
                    DB::rollBack();
                    $this->errorCount++;
                    $this->errors[] = $e->getMessage();
                    Log::error('Student Import Error: '.$e->getMessage());
                }
            }

            // Log summary
            Log::info('Student Import Completed', [
                'success' => $this->successCount,
                'errors' => $this->errorCount,
                'details' => $this->errors,
            ]);
        } catch (Exception|\Throwable $th) {
            $error = [
                'message' => $th->getMessage(),
                'file' => $th->getFile(),
                'line' => $th->getLine(),
            ];
            Log::error('Student Import Failed', $error);
            throw $th;
        }
    }
}
