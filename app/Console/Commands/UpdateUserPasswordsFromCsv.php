<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\File;

class UpdateUserPasswordsFromCsv extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:update-passwords-csv
                            {file? : Path to the CSV file (relative to the project root or absolute)}
                            {--dry-run : Simulate the password updates without saving changes}
                            {--create-missing : Create the user if they do not exist in the database}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update user passwords from a CSV file matching by Email, Username, NIM, or Name';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $filePath = $this->argument('file') ?? 'public/csv/cekuser.csv';
        $dryRun = $this->option('dry-run');

        // Resolve absolute path if relative
        if (!str_starts_with($filePath, '/') && !preg_match('/^[a-zA-Z]:[\\\\\/]/', $filePath)) {
            $filePath = base_path($filePath);
        }

        if (!File::exists($filePath)) {
            $this->error("CSV file not found at: {$filePath}");
            return 1;
        }

        $this->info("Reading user data from: {$filePath}");
        if ($dryRun) {
            $this->warn("DRY RUN MODE ENABLED. No changes will be saved to the database.");
        }

        $handle = fopen($filePath, 'r');
        if ($handle === false) {
            $this->error("Failed to open file: {$filePath}");
            return 1;
        }

        $header = fgetcsv($handle);
        if (!$header) {
            $this->error('Failed to read CSV headers.');
            fclose($handle);
            return 1;
        }

        // Clean up BOM from first element of header
        $header[0] = preg_replace('/[\x{FEFF}\x{200B}-\x{200D}]/u', '', $header[0]);

        // Find header indices case-insensitively
        $indices = [
            'name' => $this->findHeaderIndex($header, 'Name'),
            'nim' => $this->findHeaderIndex($header, 'NIM'),
            'username' => $this->findHeaderIndex($header, 'Username'),
            'email' => $this->findHeaderIndex($header, 'Email'),
            'password' => $this->findHeaderIndex($header, 'Password'),
        ];

        $this->line("Mapping columns: Name => col(" . ($indices['name'] ?? 0) . "), NIM => col(" . ($indices['nim'] ?? 1) . "), Username => col(" . ($indices['username'] ?? 2) . "), Email => col(" . ($indices['email'] ?? 3) . "), Password => col(" . ($indices['password'] ?? 4) . ")");

        $totalRows = 0;
        $updatedCount = 0;
        $skippedCount = 0;
        $failedCount = 0;
        $results = [];

        while (($row = fgetcsv($handle)) !== false) {
            $totalRows++;
            
            $name = isset($row[$indices['name']]) ? trim($row[$indices['name']]) : '';
            $nim = isset($row[$indices['nim']]) ? trim($row[$indices['nim']]) : '';
            $username = isset($row[$indices['username']]) ? trim($row[$indices['username']]) : '';
            $email = isset($row[$indices['email']]) ? trim($row[$indices['email']]) : '';
            $password = isset($row[$indices['password']]) ? trim($row[$indices['password']]) : '';

            if (empty($name) && empty($nim) && empty($username) && empty($email)) {
                $skippedCount++;
                continue;
            }

            $user = null;
            $matchField = null;

            // 1. Try Email lookup
            if (!empty($email)) {
                $user = User::where('email', $email)->first();
                if ($user) {
                    $matchField = 'Email';
                }
            }

            // 2. Try Username lookup
            if (!$user && !empty($username)) {
                $user = User::where('username', $username)->first();
                if ($user) {
                    $matchField = 'Username';
                }
            }

            // 3. Try NIM lookup
            if (!$user && !empty($nim)) {
                $user = User::where('nim', $nim)->first();
                if ($user) {
                    $matchField = 'NIM';
                }
            }

            // 4. Try Name lookup
            if (!$user && !empty($name)) {
                $user = User::where('name', $name)->first();
                if ($user) {
                    $matchField = 'Name';
                }
            }

            if ($user) {
                if (empty($password)) {
                    $this->warn("User [{$user->name}] matched, but password column in CSV is empty. Skipping password update.");
                    $results[] = [$name, $email ?: $username ?: $nim, 'Matched (Empty Pwd)', 'Skipped'];
                    $skippedCount++;
                    continue;
                }

                if ($dryRun) {
                    $this->line("<info>[Dry Run]</info> Would update password for: <comment>{$user->name}</comment> (Matched by {$matchField})");
                } else {
                    $user->password = Hash::make($password);
                    $user->save();
                    $this->line("Updated password for: <comment>{$user->name}</comment> (Matched by {$matchField})");
                }
                
                $results[] = [$user->name, $user->email ?: $user->username ?: $user->nim, "Matched by {$matchField}", $dryRun ? 'Simulated' : 'Updated'];
                $updatedCount++;
            } else {
                if ($this->option('create-missing')) {
                    if (empty($password)) {
                        $this->warn("User [{$name}] not found and cannot be created because password column in CSV is empty. Skipping.");
                        $results[] = [$name ?: 'N/A', $email ?: $username ?: $nim ?: 'N/A', 'Not Found (No Pwd)', 'Skipped'];
                        $skippedCount++;
                        continue;
                    }

                    if ($dryRun) {
                        $this->line("<info>[Dry Run]</info> Would create user: <comment>{$name}</comment> (Email: {$email}, Username: {$username}, NIM: {$nim})");
                    } else {
                        $user = new User();
                        $user->name = $name;
                        $user->email = $email ?: null;
                        $user->username = $username ?: null;
                        $user->nim = $nim ?: null;
                        $user->password = Hash::make($password);
                        $user->type_user = 'employee';
                        $user->type_study = !empty($nim) ? 'mahasiswa' : 'default';
                        $user->is_active = true;
                        $user->save();
                        $this->line("Created user: <comment>{$user->name}</comment> with CSV password");
                    }
                    $results[] = [$name, $email ?: $username ?: $nim, 'Created New', $dryRun ? 'Simulated' : 'Created'];
                    $updatedCount++;
                } else {
                    $this->error("User not found: [Name: {$name}] [Email: {$email}] [NIM: {$nim}] [Username: {$username}]");
                    $results[] = [$name ?: 'N/A', $email ?: $username ?: $nim ?: 'N/A', 'Not Found', 'Failed'];
                    $failedCount++;
                }
            }
        }

        fclose($handle);

        $this->newLine();
        $this->info("=== Summary of Results ===");
        $this->table(
            ['Name (CSV/DB)', 'Identifier', 'Match Method', 'Status'],
            $results
        );

        $this->newLine();
        $this->info("Total Rows Processed: {$totalRows}");
        $this->info("Successfully " . ($dryRun ? "Simulated" : "Updated") . ": {$updatedCount}");
        $this->info("Skipped: {$skippedCount}");
        $this->error("Failed (Not Found): {$failedCount}");

        return 0;
    }

    /**
     * Helper to find index of a header key case-insensitively, with default fallbacks.
     */
    private function findHeaderIndex(array $header, string $key): int
    {
        foreach ($header as $index => $colName) {
            if (strcasecmp(trim($colName), $key) === 0) {
                return $index;
            }
        }

        // Fallbacks if not found by name
        switch (strtolower($key)) {
            case 'name': return 0;
            case 'nim': return 1;
            case 'username': return 2;
            case 'email': return 3;
            case 'password': return 4;
        }

        return 0;
    }
}
