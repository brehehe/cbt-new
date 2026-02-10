<?php

namespace App\Console\Commands\Optimize;

use Illuminate\Console\Command;

class OptimizeApplication extends Command
{
   /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:optimize
                            {--clear : Clear all caches before optimizing}
                            {--production : Run production optimizations}
                            {--dev : Run development optimizations}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Optimize Laravel application with best practices';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🚀 Starting Laravel Application Optimization...');
        $this->newLine();

        // Clear caches if requested
        if ($this->option('clear')) {
            $this->clearCaches();
        }

        // Run optimizations based on environment
        if ($this->option('production')) {
            $this->runProductionOptimizations();
        } elseif ($this->option('dev')) {
            $this->runDevelopmentOptimizations();
        } else {
            $this->runGeneralOptimizations();
        }

        $this->newLine();
        $this->info('✅ Application optimization completed successfully!');
    }

    /**
     * Clear all application caches
     */
    protected function clearCaches()
    {
        $this->info('🧹 Clearing caches...');

        $clearCommands = [
            'cache:clear' => 'Application cache',
            'config:clear' => 'Configuration cache',
            'route:clear' => 'Route cache',
            'view:clear' => 'View cache',
            'event:clear' => 'Event cache',
            'queue:clear' => 'Queue cache',
        ];

        foreach ($clearCommands as $command => $description) {
            $this->line("  - Clearing {$description}...");
            $this->callSilent($command);
        }

        // Clear compiled services and packages
        $this->line("  - Clearing compiled classes...");
        $this->callSilent('clear-compiled');

        $this->info('✅ All caches cleared!');
        $this->newLine();
    }

    /**
     * Run production optimizations
     */
    protected function runProductionOptimizations()
    {
        $this->info('🎯 Running production optimizations...');

        $productionCommands = [
            'config:cache' => 'Caching configuration',
            'route:cache' => 'Caching routes',
            'view:cache' => 'Caching views',
            'event:cache' => 'Caching events',
            'optimize' => 'Optimizing application',
        ];

        foreach ($productionCommands as $command => $description) {
            $this->line("  - {$description}...");
            $this->callSilent($command);
        }

        // Additional production optimizations - with error handling
        $this->line("  - Generating autoload files...");
        try {
            exec('composer dump-autoload --optimize --no-dev --classmap-authoritative 2>&1', $output, $returnCode);
            if ($returnCode !== 0) {
                $this->warn("Warning: Composer autoload optimization failed");
            }
        } catch (\Exception $e) {
            $this->warn("Warning: Could not optimize composer autoload");
        }

        $this->info('✅ Production optimizations completed!');
    }

    /**
     * Run development optimizations
     */
    protected function runDevelopmentOptimizations()
    {
        $this->info('🛠️ Running development optimizations...');

        $devCommands = [
            'route:list' => 'Listing routes',
            'package:discover' => 'Discovering packages',
        ];

        foreach ($devCommands as $command => $description) {
            $this->line("  - {$description}...");
            $result = $this->call($command);

            if ($result !== 0) {
                $this->warn("Warning: Command '{$command}' completed with warnings");
            }
        }

        // Check if IDE helper package is installed by checking composer.json
        $this->generateIdeHelpers();

        $this->info('✅ Development optimizations completed!');
    }

    /**
     * Generate IDE helper files if package is available
     */
    protected function generateIdeHelpers()
    {
        // Check if ide-helper commands are available
        $availableCommands = array_keys($this->getApplication()->all());

        $ideHelperCommands = [
            'ide-helper:generate' => 'Generating IDE helper files',
            'ide-helper:models' => 'Generating model helpers',
            'ide-helper:meta' => 'Generating meta file',
        ];

        $hasIdeHelper = false;
        foreach ($ideHelperCommands as $command => $description) {
            if (in_array($command, $availableCommands)) {
                $hasIdeHelper = true;
                $this->line("  - {$description}...");
                $this->callSilent($command);
            }
        }

        if (!$hasIdeHelper) {
            $this->line("  - IDE Helper not installed, skipping...");
            $this->line("    Install with: composer require --dev barryvdh/laravel-ide-helper");
        }
    }

    /**
     * Run general optimizations
     */
    protected function runGeneralOptimizations()
    {
        $this->info('⚡ Running general optimizations...');

        $generalCommands = [
            'config:cache' => 'Caching configuration',
            'route:cache' => 'Caching routes',
            'view:cache' => 'Caching views',
            'optimize' => 'Optimizing application',
        ];

        foreach ($generalCommands as $command => $description) {
            $this->line("  - {$description}...");
            $this->callSilent($command);
        }

        $this->info('✅ General optimizations completed!');
    }

    /**
     * Check if a composer package is installed
     */
    protected function isPackageInstalled($packageName)
    {
        $composerLock = base_path('composer.lock');

        if (!file_exists($composerLock)) {
            return false;
        }

        $lockData = json_decode(file_get_contents($composerLock), true);

        if (!$lockData) {
            return false;
        }

        // Check in packages and packages-dev
        $allPackages = array_merge(
            $lockData['packages'] ?? [],
            $lockData['packages-dev'] ?? []
        );

        foreach ($allPackages as $package) {
            if ($package['name'] === $packageName) {
                return true;
            }
        }

        return false;
    }
}
