<?php

namespace App\Imports\User;

use App\Helpers\RoleHelper;
use App\Models\User;
use App\Models\User\UserDetail;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Exception;

class SupervisorImport implements ToCollection, WithHeadingRow
{
    /**
     * @param Collection $rows
     */
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

                    // Validate required fields
                    if (empty($row['name']) || empty($row['username']) || empty($row['email'])) {
                        throw new Exception("Row " . ($index + 2) . ": Name, Username, and Email are required");
                    }

                    // Check if user already exists
                    $existingUser = User::where('email', $row['email'])
                        ->where('company_id', $currentCompanyId)
                        ->where('type_user', 'employee')
                        ->first();

                    if ($existingUser) {
                        throw new Exception("Row " . ($index + 2) . ": User with email {$row['email']} already exists");
                    }

                    // Default password if not provided
                    $password = !empty($row['password']) ? $row['password'] : 'password123';

                    // Create user
                    $user = User::create([
                        'name' => $row['name'],
                        'username' => $row['username'],
                        'email' => $row['email'],
                        'password' => Hash::make($password),
                        'phone' => $row['phone'] ?? '0',
                        'company_id' => $currentCompanyId,
                        'type_user' => 'employee',
                    ]);

                    // Create user detail with supervisor specific fields
                    $detailData = [
                        'user_id' => $user->id,
                        'address' => $row['address'] ?? null,
                        'supervisor_position' => $row['position'] ?? null,
                    ];

                    UserDetail::create($detailData);

                    // Assign Supervisor role
                    $isHead = true;
                    $isActive = true;

                    RoleHelper::assignRoleToUserInCompany(
                        $user,
                        'Pengawas',
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
                    Log::error("Supervisor Import Error: " . $e->getMessage());
                }
            }

            // Log summary
            Log::info("Supervisor Import Completed", [
                'success' => $successCount,
                'errors' => $errorCount,
                'details' => $errors
            ]);
        } catch (Exception | \Throwable $th) {
            $error = [
                'message' => $th->getMessage(),
                'file' => $th->getFile(),
                'line' => $th->getLine(),
            ];
            Log::error("Supervisor Import Failed", $error);
            throw $th;
        }
    }
}
