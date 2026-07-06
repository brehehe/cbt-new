<?php

namespace App\Console\Commands;

use App\Imports\User\StudentImport;
use App\Models\Company\Company;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Maatwebsite\Excel\Facades\Excel;

class ImportStudents extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'student:import
                            {file : Path to the Excel file (relative to project root or absolute)}
                            {company_id : UUID of the company to import students into}
                            {--type=mahasiswa : The type of study (mahasiswa or general)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import students from an Excel file into a specific company';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $filePath = $this->argument('file');
        $companyId = $this->argument('company_id');
        $typeStudy = $this->option('type');

        // Resolve absolute path if relative
        if (! str_starts_with($filePath, '/') && ! preg_match('/^[a-zA-Z]:[\\\\\/]/', $filePath)) {
            $filePath = base_path($filePath);
        }

        if (! File::exists($filePath)) {
            $this->error("Excel file not found at: {$filePath}");

            return 1;
        }

        // Validate company exists
        $company = Company::find($companyId);
        if (! $company) {
            $this->error("Company with ID {$companyId} not found.");

            return 1;
        }

        $this->info("Importing students into company: {$company->name} ({$companyId})");
        $this->info("File path: {$filePath}");
        $this->info("Study type: {$typeStudy}");

        try {
            $import = new StudentImport($typeStudy, $companyId);
            Excel::import($import, $filePath);

            $this->info('Import process finished.');
            $this->line("Successful imports: {$import->successCount}");
            $this->line("Failed imports: {$import->errorCount}");

            if ($import->errorCount > 0) {
                $this->warn('Errors encountered during import:');
                foreach ($import->errors as $index => $error) {
                    $this->error('- '.$error);
                }
            }

            return 0;
        } catch (\Exception $e) {
            $this->error('Failed to execute import: '.$e->getMessage());

            return 1;
        }
    }
}
