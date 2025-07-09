<?php

namespace App\Console\Commands\ReCache;

use Illuminate\Console\Command;

class ReCacheCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 're:cache
                            {--all : Re-cache all cacheable items}
                            {--config : Re-cache configuration only}
                            {--routes : Re-cache routes only}
                            {--views : Re-cache views only}
                            {--events : Re-cache events only}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear and re-cache Laravel application components';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔄 Starting re-cache process...');
        $this->newLine();

        if ($this->option('all')) {
            $this->reCacheAll();
        } else {
            $this->reCacheSpecific();
        }

        $this->newLine();
        $this->info('✅ Re-cache process completed!');
    }

    /**
     * Re-cache all components
     */
    protected function reCacheAll()
    {
        $this->info('🔄 Re-caching all components...');

        $components = [
            'config' => ['config:clear', 'config:cache'],
            'routes' => ['route:clear', 'route:cache'],
            'views' => ['view:clear', 'view:cache'],
            'events' => ['event:clear', 'event:cache'],
        ];

        foreach ($components as $component => $commands) {
            $this->reCacheComponent($component, $commands);
        }
    }

    /**
     * Re-cache specific components based on options
     */
    protected function reCacheSpecific()
    {
        if ($this->option('config')) {
            $this->reCacheComponent('configuration', ['config:clear', 'config:cache']);
        }

        if ($this->option('routes')) {
            $this->reCacheComponent('routes', ['route:clear', 'route:cache']);
        }

        if ($this->option('views')) {
            $this->reCacheComponent('views', ['view:clear', 'view:cache']);
        }

        if ($this->option('events')) {
            $this->reCacheComponent('events', ['event:clear', 'event:cache']);
        }

        // If no specific option is provided, re-cache all
        if (!$this->option('config') && !$this->option('routes') &&
            !$this->option('views') && !$this->option('events')) {
            $this->reCacheAll();
        }
    }

    /**
     * Re-cache a specific component
     */
    protected function reCacheComponent($componentName, $commands)
    {
        $this->line("  🔄 Re-caching {$componentName}...");

        foreach ($commands as $command) {
            $this->callSilent($command);
        }

        $this->line("  ✅ {$componentName} re-cached successfully!");
    }
}
