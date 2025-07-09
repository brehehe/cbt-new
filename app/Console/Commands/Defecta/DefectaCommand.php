<?php

namespace App\Console\Commands\Defecta;

use App\Services\Defecta\DefectaService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class DefectaCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'run:defecta';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Menjalankan service defecta sekali sehari';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $defectaService = new DefectaService();
        $defectaService->runDefecta();
        $this->info('Defecta service berhasil dijalankan.');
        Log::info('Defecta service berhasil dijalankan.');
    }
}
