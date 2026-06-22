<?php

namespace App\Imports\User;

use App\Helpers\RoleHelper;
use App\Models\Study\Study;
use App\Models\User;
use App\Models\User\UserDetail;
use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class StudentImport implements ToCollection, WithHeadingRow
{
    protected ?string $typeStudy;

    public function __construct(?string $typeStudy = null)
    {
        $this->typeStudy = $typeStudy;
    }

    public function collection(Collection $rows)
    {
        try {
            $currentCompanyId = Auth::user()->company_id;
            $successCount = 0;
            $errorCount = 0;
            $errors = [];

            foreach ($rows as $index => $row) {
                try {
                    DB::beginTransaction();

                    $resolvedTypeStudy = $this->typeStudy ?? (! empty($row['type_study']) ? $row['type_study'] : 'general');

                    // Validate required fields
                    if (empty($row['name']) || empty($row['nim']) || empty($row['email'])) {
                        throw new Exception('Row '.($index + 2).': Name, NIM, and Email are required');
                    }

                    if ($resolvedTypeStudy === 'general' && empty($row['username'])) {
                        throw new Exception('Row '.($index + 2).': Username is required for general');
                    }

                    // Check if user already exists
                    $existingUser = User::where(function ($query) use ($row) {
                        $query->where('email', $row['email'])
                            ->orWhere('nim', $row['nim']);
                    })
                        ->where('company_id', $currentCompanyId)
                        ->where('type_user', 'employee')
                        ->first();

                    if ($existingUser) {
                        throw new Exception('Row '.($index + 2).': User with email/NIM already exists');
                    }

                    // Get study_id if program_studi is provided
                    $studyId = null;
                    if (! empty($row['program_studi'])) {
                        $study = Study::where('name', 'ilike', '%'.$row['program_studi'].'%')->first();
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
                        'nim' => $row['nim'],
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

                    $company = Auth::user()->company;
                    if ($company && $company->import_student_timetable) {
                        $examSessionId = null;
                        $sessionVal = $row['sesi'] ?? $row['sesi_ujian'] ?? $row['exam_session'] ?? null;
                        if (! empty($sessionVal)) {
                            $sessionName = trim($sessionVal);
                            $session = \App\Models\Master\Exam\ExamSession::where('company_id', $currentCompanyId)
                                ->where('name', $sessionName)
                                ->first();
                            if (! $session) {
                                $session = \App\Models\Master\Exam\ExamSession::create([
                                    'company_id' => $currentCompanyId,
                                    'name' => $sessionName,
                                    'code' => strtoupper(\Illuminate\Support\Str::slug($sessionName)),
                                    'is_active' => true,
                                ]);
                            }
                            $examSessionId = $session->id;
                        }

                        $examRoomId = null;
                        $roomVal = $row['ruang'] ?? $row['ruang_ujian'] ?? $row['exam_room'] ?? null;
                        if (! empty($roomVal)) {
                            $roomName = trim($roomVal);
                            $room = \App\Models\Master\Exam\ExamRoom::where('company_id', $currentCompanyId)
                                ->where('name', $roomName)
                                ->first();
                            if (! $room) {
                                $room = \App\Models\Master\Exam\ExamRoom::create([
                                    'company_id' => $currentCompanyId,
                                    'name' => $roomName,
                                    'code' => strtoupper(\Illuminate\Support\Str::slug($roomName)),
                                ]);
                            }
                            $examRoomId = $room->id;
                        }

                        $examDate = null;
                        $dateVal = $row['tanggal'] ?? $row['tanggal_ujian'] ?? $row['exam_date'] ?? null;
                        if (! empty($dateVal)) {
                            try {
                                $examDate = \Carbon\Carbon::parse($dateVal)->format('Y-m-d');
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
                    $successCount++;
                } catch (Exception $e) {
                    DB::rollBack();
                    $errorCount++;
                    $errors[] = $e->getMessage();
                    Log::error('Student Import Error: '.$e->getMessage());
                }
            }

            // Log summary
            Log::info('Student Import Completed', [
                'success' => $successCount,
                'errors' => $errorCount,
                'details' => $errors,
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
