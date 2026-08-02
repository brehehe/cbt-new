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

    public array $importResults = [];

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
            $this->importResults = [];

            $company = Company::find($currentCompanyId);

            $passwordCache = [];
            $studyCache = [];
            $sessionCache = [];
            $roomCache = [];

            foreach ($rows as $index => $row) {
                $rowNim = ! empty($row['nim']) ? trim($row['nim']) : (! empty($row['username']) ? trim($row['username']) : null);
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

                    // Check if user already exists by NIM / Username or Email
                    $existingUser = null;
                    if ($rowNim) {
                        $existingUser = User::where('company_id', $currentCompanyId)
                            ->where('type_user', 'employee')
                            ->where(function ($q) use ($rowNim) {
                                $q->where('nim', $rowNim)
                                    ->orWhere('username', $rowNim);
                            })
                            ->first();
                    } else {
                        $existingUser = User::where('email', $row['email'])
                            ->where('company_id', $currentCompanyId)
                            ->where('type_user', 'employee')
                            ->first();
                    }

                    // Get study_id if program_studi is provided
                    $studyId = null;
                    if (! empty($row['program_studi'])) {
                        $studyKey = trim($row['program_studi']);
                        if (! array_key_exists($studyKey, $studyCache)) {
                            $operator = DB::connection()->getDriverName() === 'pgsql' ? 'ilike' : 'ilike';
                            $study = Study::withoutGlobalScope('user_scope')
                                ->where('company_id', $currentCompanyId)
                                ->where('name', $operator, '%'.$studyKey.'%')
                                ->first();
                            $studyCache[$studyKey] = $study ? $study->id : null;
                        }
                        $studyId = $studyCache[$studyKey];
                    }

                    // Password handling
                    $passwordStr = ! empty($row['password']) ? (string) $row['password'] : null;
                    $hashedPassword = null;
                    if ($passwordStr !== null) {
                        if (! isset($passwordCache[$passwordStr])) {
                            $passwordCache[$passwordStr] = Hash::make($passwordStr);
                        }
                        $hashedPassword = $passwordCache[$passwordStr];
                    }

                    $typeStudy = $resolvedTypeStudy;
                    $isUpdate = false;

                    if ($existingUser) {
                        $isUpdate = true;
                        $updateData = [
                            'name' => $row['name'],
                            'email' => $row['email'],
                            'phone' => ! empty($row['phone']) ? $row['phone'] : $existingUser->phone,
                            'type_study' => $typeStudy,
                        ];
                        if ($studyId) {
                            $updateData['study_id'] = $studyId;
                        }
                        if ($hashedPassword) {
                            $updateData['password'] = $hashedPassword;
                        }
                        if ($rowNim && empty($existingUser->nim)) {
                            $updateData['nim'] = $rowNim;
                        }
                        if (! empty($row['username']) && empty($existingUser->username)) {
                            $updateData['username'] = $row['username'];
                        }

                        $existingUser->update($updateData);
                        $user = $existingUser;
                    } else {
                        // Create new user
                        if (! $hashedPassword) {
                            $defaultPasswordStr = 'password123';
                            if (! isset($passwordCache[$defaultPasswordStr])) {
                                $passwordCache[$defaultPasswordStr] = Hash::make($defaultPasswordStr);
                            }
                            $hashedPassword = $passwordCache[$defaultPasswordStr];
                        }

                        $user = User::create([
                            'name' => $row['name'],
                            'nim' => $rowNim,
                            'username' => $row['username'] ?? null,
                            'email' => $row['email'],
                            'password' => $hashedPassword,
                            'phone' => $row['phone'] ?? '0',
                            'study_id' => $studyId,
                            'company_id' => $currentCompanyId,
                            'type_user' => 'employee',
                            'type_study' => $typeStudy,
                        ]);
                    }

                    // User Detail Data
                    $detailData = [
                        'user_id' => $user->id,
                        'company_id' => $currentCompanyId,
                        'student_id' => $rowNim ?? $user->nim,
                        'nim' => $rowNim ?? $user->nim,
                    ];

                    if (! empty($row['address'])) {
                        $detailData['address'] = $row['address'];
                    }
                    if (! empty($row['faculty'])) {
                        $detailData['student_faculty'] = $row['faculty'];
                    }
                    if (! empty($row['department'])) {
                        $detailData['student_department'] = $row['department'];
                    }
                    if (! empty($row['semester'])) {
                        $detailData['student_semester'] = $row['semester'];
                    }
                    if (! empty($row['student_status'])) {
                        $detailData['student_status'] = $row['student_status'];
                    }

                    // Handle identity_number encryption if provided
                    if (! empty($row['identity_number'])) {
                        try {
                            $detailData['identity_number'] = Crypt::encryptString($row['identity_number']);
                        } catch (Exception $e) {
                            $detailData['identity_number'] = $row['identity_number'];
                        }
                    }

                    if ($company && $company->import_student_timetable) {
                        $examSessionId = null;
                        $sessionVal = $row['sesi'] ?? $row['sesi_ujian'] ?? $row['exam_session'] ?? null;
                        if (! empty($sessionVal)) {
                            $sessionName = trim($sessionVal);
                            if (! isset($sessionCache[$sessionName])) {
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
                                $sessionCache[$sessionName] = $session->id;
                            }
                            $examSessionId = $sessionCache[$sessionName];
                        }

                        $examRoomId = null;
                        $roomVal = $row['ruang'] ?? $row['ruang_ujian'] ?? $row['exam_room'] ?? null;
                        if (! empty($roomVal)) {
                            $roomName = trim($roomVal);
                            if (! isset($roomCache[$roomName])) {
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
                                $roomCache[$roomName] = $room->id;
                            }
                            $examRoomId = $roomCache[$roomName];
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

                    UserDetail::updateOrCreate(
                        ['user_id' => $user->id, 'company_id' => $currentCompanyId],
                        $detailData
                    );

                    // Assign Student role if new user
                    if (! $isUpdate) {
                        RoleHelper::assignRoleToUserInCompany(
                            $user,
                            'Mahasiswa',
                            $currentCompanyId,
                            null,
                            true,
                            true
                        );
                    }

                    DB::commit();
                    $this->successCount++;
                    $this->importResults[] = [
                        'row' => $index + 2,
                        'name' => ! empty($row['name']) ? $row['name'] : '-',
                        'nim' => ! empty($rowNim) ? $rowNim : (! empty($row['username']) ? $row['username'] : '-'),
                        'email' => ! empty($row['email']) ? $row['email'] : '-',
                        'status' => 'Berhasil',
                        'reason' => $isUpdate ? 'Data diperbarui (Update)' : 'Data baru ditambahkan',
                    ];
                } catch (Exception $e) {
                    DB::rollBack();
                    $this->errorCount++;
                    $errMsg = $e->getMessage();
                    $this->errors[] = $errMsg;
                    $this->importResults[] = [
                        'row' => $index + 2,
                        'name' => ! empty($row['name']) ? $row['name'] : '-',
                        'nim' => ! empty($rowNim) ? $rowNim : (! empty($row['username']) ? $row['username'] : '-'),
                        'email' => ! empty($row['email']) ? $row['email'] : '-',
                        'status' => 'Gagal',
                        'reason' => $errMsg,
                    ];
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
