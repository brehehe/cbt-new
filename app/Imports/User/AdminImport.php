<?php

namespace App\Imports\User;

use App\Helpers\RoleHelper;
use App\Models\User;
use App\Models\User\UserDetail;
use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class AdminImport implements ToCollection, WithHeadingRow
{
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
                        throw new Exception('Row '.($index + 2).': Name, Username, and Email are required');
                    }

                    // Check if user already exists
                    $existingUser = User::where('email', $row['email'])
                        ->where('company_id', $currentCompanyId)
                        ->where('type_user', 'employee')
                        ->first();

                    if ($existingUser) {
                        throw new Exception('Row '.($index + 2).": User with email {$row['email']} already exists");
                    }

                    // Default password if not provided
                    $password = ! empty($row['password']) ? $row['password'] : 'password123';

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

                    // Create user detail if address provided
                    if (! empty($row['address'])) {
                        UserDetail::create([
                            'user_id' => $user->id,
                            'address' => $row['address'],
                        ]);
                    }

                    // Assign Admin role
                    $isHead = true;
                    $isActive = true;

                    RoleHelper::assignRoleToUserInCompany(
                        $user,
                        'Admin',
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
                    Log::error('Admin Import Error: '.$e->getMessage());
                }
            }

            // Log summary
            Log::info('Admin Import Completed', [
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
            Log::error('Admin Import Failed', $error);
            throw $th;
        }
    }
}
