<?php

namespace App\Jobs\Module;

use App\Models\Master\Question\Module;
use App\Services\Module\ModuleService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SyncModuleQuestionsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public string $moduleId,
        public string $questionPickType,
        public array $categoryQuestionSettings,
        public array $topicQuestionSettings,
        public ?string $companyId
    ) {
    }

    public function handle(ModuleService $moduleService): void
    {
        $module = Module::find($this->moduleId);
        if (!$module) {
            return;
        }

        if ($this->questionPickType === 'category') {
            $moduleService->syncModuleQuestionsByCategory(
                $module,
                $this->categoryQuestionSettings,
                $this->companyId
            );
        } elseif ($this->questionPickType === 'topic') {
            $moduleService->syncModuleQuestionsByTopic(
                $module,
                $this->topicQuestionSettings,
                $this->companyId
            );
        }
    }
}
