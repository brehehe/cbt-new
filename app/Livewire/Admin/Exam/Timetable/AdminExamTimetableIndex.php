<?php

namespace App\Livewire\Admin\Exam\Timetable;

use App\Helpers\AlertHelper;
use App\Models\Master\Question\ModuleQuestion;
use App\Models\Master\Timetable\Timetable;
use App\Models\Timetable\TimetableQuestion;
use App\Models\User;
use App\Models\User\UserModuleQuestion;
use App\Models\User\UserTimetable;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithPagination;
use Session;

class AdminExamTimetableIndex extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap'; // atau 'tailwind' sesuai UI

    protected $queryString = [
        // 'page' => ['except' => 1],
        'search' => ['except' => ''],
    ];

    public $perPage = 5;

    public $data_id;

    public $search;

    public $code;

    public $timetable_id_supervisor;

    public $selectedSupervisors = [];

    public $availableSupervisors = [];

    public function mount()
    {
        if (session()->has('saved')) {
            AlertHelper::success(session('saved.title'), session('saved.text'));
            session()->forget('saved');

            return;
        }

        Session::forget('user_timetable_id');
    }

    public function openModalStartExam($id)
    {
        $this->data_id = $id;
        $timetable = Timetable::withoutGlobalScopes()->find($id);

        if ($timetable && !$timetable->requiresToken()) {
            $this->code = $timetable->code ?? 'NO_TOKEN';
            return $this->submitStartExam();
        }

        return $this->dispatch('open-modal', ['id' => 'modal-start-exam']);
    }

    public function closeModalStartExam()
    {
        $this->reset(['data_id', 'code']);

        return $this->dispatch('close-modal', ['id' => 'modal-start-exam']);
    }

    public function submitStartExam()
    {
        $targetTimetable = Timetable::withoutGlobalScopes()->find($this->data_id);
        $requiresToken = $targetTimetable ? $targetTimetable->requiresToken() : true;

        if ($requiresToken) {
            $this->validate([
                'code' => 'required',
            ], [
                'code.required' => 'Kode Ujian Wajib Diisi',
            ]);
        }

        try {
            DB::beginTransaction();
            $query = Timetable::withoutGlobalScopes()
                ->select('id', 'code', 'company_id', 'studys', 'is_camera', 'is_recording', 'is_streaming', 'require_token')
                ->where('company_id', Auth::user()->company_id);

            if ($requiresToken) {
                $query->where('code', trim($this->code));
            }

            $timeTable = $query->find($this->data_id);

            if (! $timeTable) {
                AlertHelper::error('Gagal', 'Token Yang Dimasukan Tidak Sesuai');

                return;
            }

            $transactionModule = $timeTable->timetableModule;

            $module = $transactionModule?->module;
            $questionPickType = $module?->question_pick_type ?? 'manual';
            $categorySettings = $transactionModule?->module?->category_question_settings ?? [];
            $topicSettings = $transactionModule?->module?->topic_question_settings ?? [];
            $materialCategorySettings = $transactionModule?->module?->material_category_question_settings ?? [];

            // Baca mode pengacakan dari setting company
            $randomQuestionMode = Auth::user()?->company?->random_question_mode ?? 'topic_grouped';

            $allowedQuestionIds = null;
            if ($module) {
                $moduleQuestionQuery = ModuleQuestion::withoutGlobalScope('user_scope')
                    ->where('module_id', $module->id);

                if ($questionPickType === 'manual') {
                    $moduleQuestionQuery->where(function ($q) {
                        $q->whereNull('question_pick_type')
                            ->orWhere('question_pick_type', 'manual');
                    });
                } else {
                    $moduleQuestionQuery->where('question_pick_type', $questionPickType);
                }

                $allowedQuestionIds = $moduleQuestionQuery->pluck('question_id')->all();
            }

            if (is_string($categorySettings)) {
                $categorySettings = json_decode($categorySettings, true) ?? [];
            }

            if (is_string($topicSettings)) {
                $topicSettings = json_decode($topicSettings, true) ?? [];
            }

            if (is_string($materialCategorySettings)) {
                $materialCategorySettings = json_decode($materialCategorySettings, true) ?? [];
            }

            $isAllQuestions = $module?->is_all_questions ?? false;
            $modulesQuestions = collect();

            if (! $isAllQuestions) {
                if ($questionPickType === 'category' && ! empty($categorySettings)) {
                    foreach ($categorySettings as $categoryId => $settings) {
                        foreach (['default', 'easy', 'medium', 'hard'] as $difficulty) {
                            $take = (int) ($settings[$difficulty] ?? 0);
                            if ($take <= 0) {
                                continue;
                            }

                            $query = TimetableQuestion::withoutGlobalScope('user_scope')
                                ->select('id', 'study_id', 'question_id', 'order')
                                ->where('timetable_module_id', $transactionModule->id)
                                ->where('category_question_id', $categoryId);

                            if (is_array($allowedQuestionIds)) {
                                $query->whereIn('question_id', $allowedQuestionIds);
                            }

                            if ($difficulty === 'default') {
                                $query->where(function ($q) {
                                    $q->where('difficulty', 'default')
                                        ->orWhereNull('difficulty');
                                });
                            } else {
                                $query->where('difficulty', $difficulty);
                            }

                            if ($transactionModule->random_question) {
                                $query->inRandomOrder();
                            } else {
                                $query->orderBy('order');
                            }

                            $modulesQuestions = $modulesQuestions->merge($query->limit($take)->get());
                        }
                    }
                } elseif ($questionPickType === 'topic' && ! empty($topicSettings)) {
                    foreach ($topicSettings as $topicId => $settings) {
                        foreach (['default', 'easy', 'medium', 'hard'] as $difficulty) {
                            $take = (int) ($settings[$difficulty] ?? 0);
                            if ($take <= 0) {
                                continue;
                            }

                            $query = TimetableQuestion::withoutGlobalScope('user_scope')
                                ->select('id', 'study_id', 'question_id', 'order')
                                ->where('timetable_module_id', $transactionModule->id)
                                ->where('topic_id', $topicId);

                            if (is_array($allowedQuestionIds)) {
                                $query->whereIn('question_id', $allowedQuestionIds);
                            }

                            if ($difficulty === 'default') {
                                $query->where(function ($q) {
                                    $q->where('difficulty', 'default')
                                        ->orWhereNull('difficulty');
                                });
                            } else {
                                $query->where('difficulty', $difficulty);
                            }

                            if ($transactionModule->random_question) {
                                $query->inRandomOrder();
                            } else {
                                $query->orderBy('order');
                            }

                            $modulesQuestions = $modulesQuestions->merge($query->limit($take)->get());
                        }
                    }
                } elseif ($questionPickType === 'material_category' && ! empty($materialCategorySettings)) {
                    foreach ($materialCategorySettings as $materialCategoryId => $settings) {
                        foreach (['default', 'easy', 'medium', 'hard'] as $difficulty) {
                            $take = (int) ($settings[$difficulty] ?? 0);
                            if ($take <= 0) {
                                continue;
                            }

                            $query = TimetableQuestion::withoutGlobalScope('user_scope')
                                ->select('id', 'study_id', 'question_id', 'order')
                                ->where('timetable_module_id', $transactionModule->id)
                                ->where('material_category_id', $materialCategoryId);

                            if (is_array($allowedQuestionIds)) {
                                $query->whereIn('question_id', $allowedQuestionIds);
                            }

                            if ($difficulty === 'default') {
                                $query->where(function ($q) {
                                    $q->where('difficulty', 'default')
                                        ->orWhereNull('difficulty');
                                });
                            } else {
                                $query->where('difficulty', $difficulty);
                            }

                            if ($transactionModule->random_question) {
                                $query->inRandomOrder();
                            } else {
                                $query->orderBy('order');
                            }

                            $modulesQuestions = $modulesQuestions->merge($query->limit($take)->get());
                        }
                    }
                }
            }

            if ($modulesQuestions->isEmpty()) {
                $query = TimetableQuestion::withoutGlobalScope('user_scope')
                    ->select('id', 'study_id', 'question_id', 'order', 'topic_id')
                    ->where('timetable_module_id', $transactionModule->id);

                if (is_array($allowedQuestionIds)) {
                    $query->whereIn('question_id', $allowedQuestionIds);
                }

                if ($transactionModule->random_question) {
                    if ($randomQuestionMode === 'topic_grouped') {
                        // Urut berdasarkan topic_id asc, soal dalam topik tetap diacak dari main loop
                        $query->orderBy('topic_id', 'asc');
                    } else {
                        $query->inRandomOrder();
                    }
                } else {
                    $query->orderBy('order');
                }

                $modulesQuestions = $query->get();
            }

            if ($modulesQuestions->isNotEmpty()) {
                $modulesQuestions = $modulesQuestions->unique('id')->values();

                if ($transactionModule->random_question) {
                    if ($randomQuestionMode === 'fully_random') {
                        // Acak Total: acak semua soal sepenuhnya
                        $modulesQuestions = $modulesQuestions->shuffle()->values();
                    } else {
                        // Acak Per Topik (default: topic_grouped):
                        // Soal sudah diambil per-topik secara acak (inRandomOrder per loop)
                        // Kita hanya perlu memastikan urutan topik tetap,
                        // tapi soal dalam setiap topik sudah teracak dari query DB
                        // Tidak perlu shuffle ulang — urutan collection sudah: topik1(acak), topik2(acak), ...
                        $modulesQuestions = $modulesQuestions->values();
                    }
                } else {
                    $modulesQuestions = $modulesQuestions->sortBy('order')->values();
                }

                $selectedTimetableQuestionIds = $modulesQuestions->pluck('id')->filter()->values();
                $selectedQuestionIds = $modulesQuestions->pluck('question_id')->filter()->values();

                if ($selectedTimetableQuestionIds->isNotEmpty()) {
                    TimetableQuestion::withoutGlobalScope('user_scope')
                        ->whereIn('id', $selectedTimetableQuestionIds)
                        ->update(['is_check' => true]);
                }

                if ($module && $selectedQuestionIds->isNotEmpty()) {
                    ModuleQuestion::withoutGlobalScope('user_scope')
                        ->where('module_id', $module->id)
                        ->whereIn('question_id', $selectedQuestionIds)
                        ->update(['is_check' => true]);
                }
            }

            $UserTimetable = UserTimetable::firstOrCreate([
                'user_id' => Auth::id(),
                'timetable_id' => $timeTable->id,
            ], [
                'start_process' => Carbon::now(),
                'studys' => $timeTable->studys,
                'is_camera' => $timeTable?->is_camera ?? false,
                'is_recording' => $timeTable?->is_recording ?? false,
                'is_streaming' => $timeTable?->is_streaming ?? false,
            ]);

            // Prevent double/duplicate insertion if questions already exist for this user timetable
            $hasExistingQuestions = UserModuleQuestion::withoutGlobalScopes()
                ->where('user_timetable_id', $UserTimetable->id)
                ->exists();

            if (! $hasExistingQuestions) {
                $userModuleQuestionsData = [];
                $now = Carbon::now();
                $companyId = Auth::user()->company_id;

                foreach ($modulesQuestions as $index => $moduleQuestion) {
                    $userModuleQuestionsData[] = [
                        'id' => (string) Str::uuid(),
                        'user_timetable_id' => $UserTimetable->id,
                        'timetable_module_id' => $transactionModule->id,
                        'timetable_question_id' => $moduleQuestion->id,
                        'study_id' => $moduleQuestion->study_id,
                        'company_id' => $companyId,
                        'order' => $index + 1,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ];
                }

                if (! empty($userModuleQuestionsData)) {
                    // Bulk insert in chunks to avoid single query limits if very large
                    $chunks = array_chunk($userModuleQuestionsData, 200);
                    foreach ($chunks as $chunk) {
                        UserModuleQuestion::insert($chunk);
                    }
                }
            }

            DB::commit();
            session()->flash('saved', [
                'title' => 'Ujian Telah Dimulai!',
                'text' => 'Anda berhasil memulai ujian!',
            ]);

            if (Auth::user()->hasRole('Mahasiswa')) {
                return redirect()->route('admin.exam.warning');
            }

            return redirect()->route('admin.exam.detail.react', [
                'userTimetableId' => $UserTimetable->id,
            ]);
        } catch (\Throwable $th) {
            DB::rollback();
            AlertHelper::error('Gagal'.$th->getMessage());

            return Log::error($th->getMessage());
        }
    }

    // public function hydrate()
    // {
    //     $this->resetPage();
    // }

    public function confirmBackExam($id)
    {
        $userTimetable = UserTimetable::withoutGlobalScopes()->with('timetable')->find($id);
        if (! $userTimetable) {
            return AlertHelper::error('Gagal', 'Data Ujian Tidak Ditemukan');
        }

        if (in_array($userTimetable->status, ['done', 'suspend'])) {
            if ($userTimetable->canResetOrRepeat()) {
                Session::put('user_timetable_id', $id);
                return redirect()->route('admin.exam.detail.react', [
                    'userTimetableId' => $id,
                ]);
            }

            return AlertHelper::error('Gagal', 'Ujian Sudah Selesai');
        }

        if ($userTimetable->status == 'warning') {
            Session::put('user_timetable_id', $id);

            return redirect()->route('admin.exam.warning');
        }

        if ($userTimetable->status == 'exam') {
            Session::put('user_timetable_id', $id);

            return redirect()->route('admin.exam.detail.react', [
                'userTimetableId' => $id,
            ]);
        }
    }

    public function repeatExam($userTimetableId)
    {
        $oldUserTimetable = UserTimetable::withoutGlobalScopes()
            ->with(['timetable' => function ($q) {
                $q->withoutGlobalScopes();
            }])
            ->find($userTimetableId);

        if (! $oldUserTimetable || ! $oldUserTimetable->canResetOrRepeat()) {
            return AlertHelper::error('Gagal', 'Jadwal ujian ini tidak diizinkan untuk diulang.');
        }

        try {
            DB::beginTransaction();

            $timetable = $oldUserTimetable->timetable;
            $userId = Auth::id() ?: $oldUserTimetable->user_id;

            $attemptCount = UserTimetable::withoutGlobalScopes()
                ->where('user_id', $userId)
                ->where('timetable_id', $timetable->id)
                ->count();

            $nextAttempt = $attemptCount + 1;

            $newUserTimetable = UserTimetable::create([
                'user_id' => $userId,
                'timetable_id' => $timetable->id,
                'start_process' => Carbon::now(),
                'studys' => $timetable->studys,
                'company_id' => $timetable->company_id,
                'is_camera' => $timetable?->is_camera ?? false,
                'is_recording' => $timetable?->is_recording ?? false,
                'is_streaming' => $timetable?->is_streaming ?? false,
                'status' => 'warning',
                'attempt' => $nextAttempt,
            ]);

            $oldQuestions = UserModuleQuestion::withoutGlobalScopes()
                ->where('user_timetable_id', $oldUserTimetable->id)
                ->orderBy('order')
                ->get();

            $now = Carbon::now();
            $userModuleQuestionsData = [];

            if ($oldQuestions->isNotEmpty()) {
                foreach ($oldQuestions as $q) {
                    $userModuleQuestionsData[] = [
                        'id' => (string) Str::uuid(),
                        'user_timetable_id' => $newUserTimetable->id,
                        'timetable_module_id' => $q->timetable_module_id,
                        'timetable_question_id' => $q->timetable_question_id,
                        'study_id' => $q->study_id,
                        'company_id' => $q->company_id,
                        'order' => $q->order,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ];
                }
            } else if ($timetable->timetableModule) {
                $tqs = TimetableQuestion::withoutGlobalScope('user_scope')
                    ->where('timetable_module_id', $timetable->timetableModule->id)
                    ->get();
                foreach ($tqs as $index => $tq) {
                    $userModuleQuestionsData[] = [
                        'id' => (string) Str::uuid(),
                        'user_timetable_id' => $newUserTimetable->id,
                        'timetable_module_id' => $timetable->timetableModule->id,
                        'timetable_question_id' => $tq->id,
                        'study_id' => $tq->study_id,
                        'company_id' => $timetable->company_id,
                        'order' => $index + 1,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ];
                }
            }

            if (! empty($userModuleQuestionsData)) {
                foreach (array_chunk($userModuleQuestionsData, 200) as $chunk) {
                    UserModuleQuestion::insert($chunk);
                }
            }

            DB::commit();

            Session::put('user_timetable_id', $newUserTimetable->id);
            if (Auth::user()?->hasRole('Mahasiswa')) {
                return redirect()->route('admin.exam.warning');
            }

            return redirect()->route('admin.exam.detail.react', [
                'userTimetableId' => $newUserTimetable->id,
            ]);
        } catch (\Throwable $th) {
            DB::rollback();
            return AlertHelper::error('Gagal', 'Gagal membuat sesi pengulangan: ' . $th->getMessage());
        }
    }

    public function openModalSupervisor($id)
    {
        $this->timetable_id_supervisor = $id;
        $timetable = Timetable::find($id);

        if (! $timetable) {
            AlertHelper::error('Gagal', 'Jadwal tidak ditemukan.');
            return;
        }

        $supervisors = $timetable->supervisors;
        if (is_string($supervisors)) {
            $supervisors = json_decode($supervisors, true) ?? [];
        }
        $this->selectedSupervisors = is_array($supervisors) ? $supervisors : [];

        $this->availableSupervisors = User::companyRole('Pengawas', Auth::user()->company_id)
            ->select('name', 'id')
            ->get()
            ->pluck('name', 'id')
            ->toArray();

        if (empty($this->availableSupervisors)) {
            $this->availableSupervisors = User::role(['Pengawas', 'pengawas'])
                ->select('name', 'id')
                ->get()
                ->pluck('name', 'id')
                ->toArray();
        }

        return $this->dispatch('open-modal', ['id' => 'modal-change-supervisor']);
    }

    public function closeModalSupervisor()
    {
        $this->reset(['timetable_id_supervisor', 'selectedSupervisors']);
        return $this->dispatch('close-modal', ['id' => 'modal-change-supervisor']);
    }

    public function saveSupervisor()
    {
        try {
            $timetable = Timetable::find($this->timetable_id_supervisor);
            if (! $timetable) {
                AlertHelper::error('Gagal', 'Jadwal tidak ditemukan.');
                return;
            }

            $timetable->update([
                'supervisors' => array_values(array_filter($this->selectedSupervisors))
            ]);

            AlertHelper::success('Berhasil', 'Pengawas ujian berhasil diperbarui.');
            return $this->closeModalSupervisor();
        } catch (\Throwable $th) {
            Log::error('saveSupervisor error: ' . $th->getMessage());
            AlertHelper::error('Gagal', 'Gagal memperbarui pengawas: ' . $th->getMessage());
        }
    }

    public function render()
    {
        $userTimetableStatusDone = UserTimetable::withoutGlobalScopes()
            ->where('user_id', Auth::id())
            ->whereIn('status', ['done', 'suspend'])
            ->whereHas('timetable', function ($q) {
                $q->withoutGlobalScopes()
                  ->where(function ($sub) {
                      $sub->whereNull('allow_repeat')->orWhere('allow_repeat', false);
                  })
                  ->where(function ($sub) {
                      $sub->whereNull('is_simulation')->orWhere('is_simulation', 'false');
                  });
            })
            ->pluck('timetable_id')
            ->toArray();

        $auth = Auth::user();

        $timetables = Timetable::query()
            ->with(['timetableModule.questionType', 'userTimetable'])
            // ->whereNotNull('code')
            ->when($this->search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'ilike', '%'.$search.'%')
                        ->orWhere('description', 'ilike', '%'.$search.'%');
                });
            })
            // Menampilkan ujian resmi dan simulasi yang diizinkan
            ->where(function ($q) {
                $q->where('is_simulation', 'false')
                  ->orWhere('is_simulation', 'true');
            })
            ->where(function ($query) {
                $now = Carbon::now();
                $query->where('start_time', '<=', $now->copy()->addMinutes(5))
                    ->where('end_time', '>=', $now->copy()->subMinutes(5));
            });

        // Filter berdasarkan study_id user
        // if ($auth->study_id) {
        //     // $timetables->where('study_id', $auth->study_id);

        //     $timetables->where(function ($query) use ($auth) {
        //         $query->whereNull('studys')
        //             ->orWhere('studys', 'ilike', '%\\\"' . $auth->study_id . '\\\"%');
        //     });
        // }

        if ($auth->hasRole(['Mahasiswa'])) {
            // $timetables->where('study_id', $auth->study_id);
            $classmateIds = $auth->classmateStudents()->pluck('classmate_id')->toArray();
            if (! empty($classmateIds)) {
                $timetables->whereIn('classmate_id', $classmateIds);
            } else {
                $timetables->whereNull('classmate_id');
            }
        }

        if (! empty($userTimetableStatusDone)) {
            $timetables->whereNotIn('id', $userTimetableStatusDone);
        }

        $viewName = config('app.new_template', false)
            ? 'livewire.admin.exam.timetable.admin-exam-timetable-index-new'
            : 'livewire.admin.exam.timetable.admin-exam-timetable-index';

        $layoutName = config('app.new_template', false)
            ? 'layout.app-horizontal'
            : 'layout.app';

        return view($viewName, [
            'timetables' => $timetables->paginate($this->perPage),
        ])
            ->extends($layoutName)
            ->section('content');
    }
}
