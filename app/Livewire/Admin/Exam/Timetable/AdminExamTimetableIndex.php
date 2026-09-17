<?php

namespace App\Livewire\Admin\Exam\Timetable;

use App\Helpers\AlertHelper;
use App\Models\Master\DigitalBook\DigitalBook;
use App\Models\Master\Question\ModuleQuestion;
use App\Models\Master\Timetable\Timetable;
use App\Models\Master\Timetable\TimetableAttendance;
use App\Models\Master\Timetable\TimetableDetail;
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
use Milon\Barcode\DNS2D;
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

    // LEMES Mode Properties
    public $selectedDigitalBook = null;
    public $selectedDetail = null;
    public $materialTokenInput = '';
    public $studentQrBase64 = null;
    public $studentQrInfo = [];
    public $scan_target_detail_id = null;
    public $target_detail_id = null;
    public $manual_qr_code = '';

    public function mount()
    {
        if (session()->has('saved')) {
            AlertHelper::success(session('saved.title'), session('saved.text'));
            session()->forget('saved');

            return;
        }

        Session::forget('user_timetable_id');
    }

    public function openModalStartExam($id, $detailId = null)
    {
        $this->data_id = $id;
        $this->target_detail_id = $detailId;
        $timetable = Timetable::withoutGlobalScopes()->find($id);
        $detail = $detailId ? TimetableDetail::find($detailId) : null;

        $requiresToken = $detail ? (bool)$detail->require_token : ($timetable ? $timetable->requiresToken() : true);

        if (!$requiresToken) {
            $this->code = $detail?->token ?? $timetable?->code ?? 'NO_TOKEN';
            return $this->submitStartExam();
        }

        return $this->dispatch('open-modal', ['id' => 'modal-start-exam']);
    }

    public function closeModalStartExam()
    {
        $this->reset(['data_id', 'target_detail_id', 'code']);

        return $this->dispatch('close-modal', ['id' => 'modal-start-exam']);
    }

    public function submitStartExam()
    {
        $targetTimetable = Timetable::withoutGlobalScopes()->find($this->data_id);
        $targetDetail = $this->target_detail_id 
            ? TimetableDetail::find($this->target_detail_id) 
            : ($targetTimetable?->timetableDetails?->first());

        $checkItem = $targetDetail ?? $targetTimetable;

        if ($checkItem) {
            $now = Carbon::now();
            if ($checkItem->start_time) {
                $earlyAllowed = Carbon::parse($checkItem->start_time)->subMinutes(5);
                if ($now->lt($earlyAllowed)) {
                    return AlertHelper::error('Belum Dibuka', 'Jadwal ujian belum dapat dibuka. Ujian dimulai pukul ' . Carbon::parse($checkItem->start_time)->format('H:i') . ' (dapat diakses 5 menit sebelum waktu mulai).');
                }
            }
            if ($checkItem->end_time) {
                $lateAllowed = Carbon::parse($checkItem->end_time)->addMinutes(5);
                if ($now->gt($lateAllowed)) {
                    return AlertHelper::error('Jadwal Berakhir', 'Waktu pelaksanaan ujian ini telah berakhir.');
                }
            }
        }

        if (is_lemes() && $targetTimetable) {
            $mustAttend = $targetDetail ? (bool)$targetDetail->require_attendance : $targetTimetable->timetableDetails()->where('require_attendance', true)->exists();
            if ($mustAttend) {
                $attended = TimetableAttendance::where('timetable_id', $targetTimetable->id)
                    ->where(function ($q) use ($targetDetail) {
                        if ($targetDetail) {
                            $q->whereNull('timetable_detail_id')->orWhere('timetable_detail_id', $targetDetail->id);
                        }
                    })
                    ->where('user_id', Auth::id())
                    ->exists();
                if (!$attended) {
                    $this->openStudentScanner($targetDetail?->id);
                    return AlertHelper::error('Presensi Wajib', 'Anda belum melakukan scan absensi kehadiran untuk jadwal ini. Silakan scan QRCODE sesi yang telah dicetak oleh Admin / Pengawas.');
                }
            }
        }

        $requiresToken = $targetDetail ? (bool)$targetDetail->require_token : ($targetTimetable ? $targetTimetable->requiresToken() : true);
        $expectedToken = $targetDetail?->token ?: ($targetTimetable?->code);

        if ($requiresToken) {
            $this->validate([
                'code' => 'required',
            ], [
                'code.required' => 'Kode Ujian Wajib Diisi',
            ]);
        }

        try {
            DB::beginTransaction();
            if ($requiresToken) {
                $tokenMatches = (trim($this->code) === trim($expectedToken)) || (trim($this->code) === trim($targetTimetable?->code));
                if (! $tokenMatches) {
                    AlertHelper::error('Gagal', 'Token Yang Dimasukan Tidak Sesuai');

                    return;
                }
            }

            $timeTable = Timetable::withoutGlobalScopes()
                ->select('id', 'code', 'company_id', 'studys', 'is_camera', 'is_recording', 'is_streaming', 'require_token')
                ->where('company_id', Auth::user()->company_id)
                ->find($this->data_id);

            if (! $timeTable) {
                AlertHelper::error('Gagal', 'Data jadwal ujian tidak ditemukan');

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

                // Apply question pool limit (e.g. 100 questions out of 500 pool)
                $limitTotalQuestions = $timeTable->total_questions ?? $transactionModule->total_questions ?? $module?->total_questions ?? null;
                if ($limitTotalQuestions && (int) $limitTotalQuestions > 0 && $modulesQuestions->count() > (int) $limitTotalQuestions) {
                    $modulesQuestions = $modulesQuestions->take((int) $limitTotalQuestions)->values();
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
            return redirect()->route('admin.exam.history-timetable.detail', [
                'timetable_id' => $userTimetable->timetable_id,
                'user_timetable_id' => $userTimetable->id,
            ]);
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

    public function viewResult($timetableId, $userTimetableId)
    {
        return redirect()->route('admin.exam.history-timetable.detail', [
            'timetable_id' => $timetableId,
            'user_timetable_id' => $userTimetableId,
        ]);
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

    // ==========================================
    // LEMES METHODS (STUDENT QR & DIGITAL BOOK)
    // ==========================================

    public function showMyQrCode()
    {
        $user = Auth::user();
        if (!$user) return;

        $payload = json_encode([
            'user_id' => $user->id,
            'username' => $user->username,
            'name' => $user->name,
        ]);

        $this->studentQrBase64 = (new DNS2D())->getBarcodePNG($payload, 'QRCODE', 6, 6);
        $this->studentQrInfo = [
            'name' => $user->name,
            'username' => $user->username,
            'company' => $user->company?->name ?? 'CBT System',
            'role' => student_label(),
        ];

        return $this->dispatch('open-modal', ['id' => 'modal-student-qr']);
    }

    public function closeMyQrCode()
    {
        $this->reset(['studentQrBase64', 'studentQrInfo']);
        return $this->dispatch('close-modal', ['id' => 'modal-student-qr']);
    }

    public function openMaterial($detailId)
    {
        $detail = TimetableDetail::with(['digitalBook.category', 'timetable'])->find($detailId);
        if (!$detail || !$detail->digitalBook) {
            return AlertHelper::error('Gagal', 'Data materi tidak ditemukan.');
        }

        // Cek waktu pelaksanaan materi (toleransi 5 menit sebelum mulai hingga selesai)
        $now = Carbon::now();
        if ($detail->start_time) {
            $earlyAllowed = Carbon::parse($detail->start_time)->subMinutes(5);
            if ($now->lt($earlyAllowed)) {
                return AlertHelper::error('Belum Dibuka', 'Materi pembelajaran belum dapat dibuka. Materi dimulai pukul ' . Carbon::parse($detail->start_time)->format('H:i') . ' (dapat diakses 5 menit sebelum waktu mulai).');
            }
        }
        if ($detail->end_time) {
            $lateAllowed = Carbon::parse($detail->end_time)->addMinutes(5);
            if ($now->gt($lateAllowed)) {
                return AlertHelper::error('Jadwal Berakhir', 'Waktu akses untuk materi pembelajaran ini telah berakhir.');
            }
        }

        $this->selectedDetail = $detail;

        // Cek absensi jika wajib
        if ($detail->require_attendance) {
            $attended = TimetableAttendance::where('timetable_id', $detail->timetable_id)
                ->where(function ($q) use ($detail) {
                    $q->whereNull('timetable_detail_id')
                      ->orWhere('timetable_detail_id', $detail->id);
                })
                ->where('user_id', Auth::id())
                ->exists();

            if (!$attended) {
                $this->openStudentScanner($detail->id);
                return AlertHelper::error('Presensi Wajib', 'Anda belum melakukan scan absensi untuk materi ini. Silakan scan QRCODE sesi yang telah dicetak oleh Admin / Pengawas.');
            }
        }

        // Cek token jika wajib
        if ($detail->require_token) {
            $this->materialTokenInput = '';
            return $this->dispatch('open-modal', ['id' => 'modal-material-token']);
        }

        // Buka materi langsung
        $this->selectedDigitalBook = $detail->digitalBook;
        return $this->dispatch('open-modal', ['id' => 'modal-view-material']);
    }

    public function submitMaterialToken()
    {
        if (!$this->selectedDetail) {
            return AlertHelper::error('Gagal', 'Detail materi tidak ditemukan.');
        }

        if (trim($this->materialTokenInput) !== trim($this->selectedDetail->token)) {
            return AlertHelper::error('Token Salah', 'Token materi yang Anda masukkan tidak sesuai.');
        }

        $this->selectedDigitalBook = $this->selectedDetail->digitalBook;
        $this->dispatch('close-modal', ['id' => 'modal-material-token']);
        return $this->dispatch('open-modal', ['id' => 'modal-view-material']);
    }

    public function closeMaterialToken()
    {
        $this->reset(['materialTokenInput']);
        return $this->dispatch('close-modal', ['id' => 'modal-material-token']);
    }

    public function closeMaterial()
    {
        $this->reset(['selectedDigitalBook', 'selectedDetail', 'materialTokenInput']);
        return $this->dispatch('close-modal', ['id' => 'modal-view-material']);
    }

    // ==========================================
    // STUDENT CAMERA SCANNER FOR ATTENDANCE
    // ==========================================

    public function openStudentScanner($detailId = null)
    {
        $this->scan_target_detail_id = $detailId;
        $this->manual_qr_code = '';
        return $this->dispatch('open-modal', ['id' => 'modal-student-camera-scan']);
    }

    public function closeStudentScanner()
    {
        $this->reset(['scan_target_detail_id', 'manual_qr_code']);
        return $this->dispatch('close-modal', ['id' => 'modal-student-camera-scan']);
    }

    public function processStudentAttendanceScan($qrPayload)
    {
        try {
            $user = Auth::user();
            if (!$user) {
                $this->dispatch('student-scan-error', ['message' => 'Sesi login Anda telah kedaluwarsa. Silakan login kembali.']);
                return;
            }

            $cleanPayload = trim($qrPayload);
            if (empty($cleanPayload)) {
                $this->dispatch('student-scan-error', ['message' => 'QRCODE kosong atau tidak terbaca!']);
                return;
            }

            $detailCode = null;
            $detailId = null;

            $decoded = json_decode($cleanPayload, true);
            if (is_array($decoded)) {
                $detailCode = $decoded['code'] ?? null;
                $detailId = $decoded['detail_id'] ?? $decoded['timetable_detail_id'] ?? null;
            } else {
                $detailCode = $cleanPayload;
            }

            // Cari detail berdasarkan Code atau ID (dengan validasi UUID untuk PostgreSQL)
            $isPayloadUuid = Str::isUuid($cleanPayload);
            $isDetailIdUuid = $detailId && Str::isUuid($detailId);

            $detail = TimetableDetail::withoutGlobalScopes()
                ->with(['timetable.classmate.classmateStudents', 'examRoom', 'examSession', 'module', 'digitalBook'])
                ->where(function ($q) use ($detailCode, $detailId, $cleanPayload, $isPayloadUuid, $isDetailIdUuid) {
                    if ($detailCode) {
                        $q->where('code', $detailCode);
                    }
                    if ($isDetailIdUuid) {
                        $q->orWhere('id', $detailId);
                    }
                    if ($isPayloadUuid) {
                        $q->orWhere('id', $cleanPayload);
                    }
                })
                ->first();

            // Jika tidak langsung ketemu detail, coba cari timetable dan ambil detail pertamanya
            if (!$detail) {
                $timetable = Timetable::withoutGlobalScopes()
                    ->with(['timetableDetails.examRoom', 'timetableDetails.examSession', 'timetableDetails.module', 'timetableDetails.digitalBook', 'classmate.classmateStudents'])
                    ->where(function ($q) use ($cleanPayload, $isPayloadUuid) {
                        $q->where('code', $cleanPayload);
                        if ($isPayloadUuid) {
                            $q->orWhere('id', $cleanPayload);
                        }
                    })
                    ->first();

                if ($timetable && $timetable->timetableDetails->isNotEmpty()) {
                    $detail = $timetable->timetableDetails->first();
                }
            }

            if (!$detail || !$detail->timetable) {
                $this->dispatch('student-scan-error', [
                    'message' => 'QRCODE sesi tidak terdaftar di sistem. Pastikan Anda scan lembar QRCODE yang dicetak oleh Admin / Pengawas.'
                ]);
                return;
            }

            $timetable = $detail->timetable;

            // Validasi jadwal dan toleransi waktu presensi (5 menit sebelum waktu mulai hingga waktu selesai)
            if ($detail->start_time) {
                $now = Carbon::now();
                $earlyAllowed = Carbon::parse($detail->start_time)->subMinutes(5);
                if ($now->lt($earlyAllowed)) {
                    $this->dispatch('student-scan-error', [
                        'message' => 'Presensi belum dibuka. Sesi ini dimulai pukul ' . Carbon::parse($detail->start_time)->format('H:i') . ' (dapat presensi 5 menit sebelum waktu mulai).'
                    ]);
                    return;
                }
                if ($detail->end_time) {
                    $lateAllowed = Carbon::parse($detail->end_time)->addMinutes(5);
                    if ($now->gt($lateAllowed)) {
                        $this->dispatch('student-scan-error', [
                            'message' => 'Waktu presensi untuk sesi ini telah berakhir.'
                        ]);
                        return;
                    }
                }
            }

            // Validasi apakah user terdaftar di kelas (classmateStudent)
            if ($timetable->classmate) {
                $isEnrolled = $timetable->classmate->classmateStudents()->where('user_id', $user->id)->exists();
                if (!$isEnrolled) {
                    $studentLabel = student_label();
                    $this->dispatch('student-scan-error', [
                        'message' => "Anda tidak terdaftar sebagai {$studentLabel} di kelas " . ($timetable->classmate->name ?? '-') . " untuk jadwal ini!"
                    ]);
                    return;
                }
            }

            // Simpan / update Presensi
            TimetableAttendance::updateOrCreate(
                [
                    'timetable_id' => $timetable->id,
                    'timetable_detail_id' => $detail->id,
                    'user_id' => $user->id,
                ],
                [
                    'attended_at' => now(),
                    'status' => 'present',
                    'method' => 'student_scan',
                    'company_id' => $user->company_id,
                ]
            );

            $activityName = $detail->isMaterial()
                ? ($detail->digitalBook?->title ?? 'Materi Pembelajaran')
                : ($detail->module?->name ?? 'Ujian CBT');

            $sessionName = $detail->examSession?->name ?? 'Sesi Pelaksanaan';
            $roomName = $detail->examRoom?->name ?? 'Ruang';

            $this->dispatch('student-scan-success', [
                'message' => "Presensi Berhasil! Kehadiran Anda pada {$sessionName} ({$activityName} - {$roomName}) telah dicatat.",
                'detail_id' => $detail->id,
                'timetable_id' => $timetable->id,
            ]);

            AlertHelper::success('Presensi Berhasil', "Kehadiran Anda pada sesi {$sessionName} ({$activityName}) telah berhasil dicatat.");

        } catch (\Throwable $th) {
            Log::error('processStudentAttendanceScan error: ' . $th->getMessage());
            $this->dispatch('student-scan-error', [
                'message' => 'Terjadi kesalahan sistem saat memproses presensi: ' . $th->getMessage()
            ]);
        }
    }

    public function submitManualQrCode()
    {
        if (empty(trim($this->manual_qr_code))) {
            return AlertHelper::error('Kode Kosong', 'Silakan masukkan ID QRCODE sesi terlebih dahulu.');
        }

        return $this->processStudentAttendanceScan($this->manual_qr_code);
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

        $now = Carbon::now();
        $earlyOpen = $now->copy()->addMinutes(5); // Toleransi buka 5 menit sebelum waktu mulai (contoh mulai 08:00, muncul mulai 07:55)
        $lateEnd = $now->copy()->subMinutes(5);   // Toleransi setelah waktu selesai

        // Filter detail kegiatan untuk Mode LEMES:
        // 1. Sesuai jadwal waktu (+ toleransi 5 menit)
        // 2. Jika require_attendance = true, HANYA MUNCUL JIKA SISWA SUDAH SCAN ABSENSI
        // 3. Jika require_attendance = false / null, muncul langsung sesuai jadwal waktu
        $lemesDetailFilter = function ($q) use ($earlyOpen, $lateEnd) {
            $q->where(function ($timeQ) use ($earlyOpen, $lateEnd) {
                $timeQ->where(function ($t) use ($earlyOpen) {
                    $t->whereNull('start_time')
                      ->orWhere('start_time', '<=', $earlyOpen);
                })
                ->where(function ($t) use ($lateEnd) {
                    $t->whereNull('end_time')
                      ->orWhere('end_time', '>=', $lateEnd);
                });
            })
            ->where(function ($attQ) {
                $attQ->where(function ($noReq) {
                    $noReq->where('require_attendance', false)
                          ->orWhereNull('require_attendance');
                })
                ->orWhere(function ($mustAtt) {
                    $mustAtt->where('require_attendance', true)
                            ->whereHas('attendances', function ($a) {
                                $a->where('user_id', Auth::id());
                            });
                });
            });
        };

        $withRelations = ['timetableModule.questionType', 'userTimetable'];
        if (is_lemes()) {
            $withRelations[] = 'classmate';
            $withRelations['timetableDetails'] = function ($q) use ($lemesDetailFilter) {
                $lemesDetailFilter($q);
                $q->with(['module', 'digitalBook.category', 'examRoom', 'examSession'])
                  ->orderBy('order', 'asc');
            };
            $withRelations['attendances'] = function ($q) {
                $q->where('user_id', Auth::id());
            };
        }

        $timetables = Timetable::query()
            ->with($withRelations)
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
            ->where(function ($query) use ($earlyOpen, $lateEnd, $lemesDetailFilter) {
                if (is_lemes()) {
                    // Mode LEMES: Jadwal muncul jika memiliki rincian detail yang sesuai waktu dan syarat absensi (jika wajib, harus sudah absensi)
                    $query->whereHas('timetableDetails', function ($sub) use ($lemesDetailFilter) {
                        $lemesDetailFilter($sub);
                    });
                } else {
                    // Standar CBT: start_time <= earlyOpen (now + 5 min) dan end_time >= lateEnd (now - 5 min)
                    $query->where(function ($q) use ($earlyOpen, $lateEnd) {
                        $q->where(function ($t) use ($earlyOpen) {
                            $t->whereNull('start_time')
                              ->orWhere('start_time', '<=', $earlyOpen);
                        })
                        ->where(function ($t) use ($lateEnd) {
                            $t->whereNull('end_time')
                              ->orWhere('end_time', '>=', $lateEnd);
                        });
                    });
                }

                // Tetap tampil jika siswa sedang dalam pengerjaan ujian aktif (exam atau warning)
                $query->orWhereHas('userTimetable', function ($ut) {
                    $ut->whereIn('status', ['exam', 'warning']);
                });
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

        if (! is_lemes()) {
            // Ketika is_lemes = false, bagian materi tidak perlu muncul (hanya ujian CBT)
            $timetables->where(function ($q) {
                $q->whereNotNull('module_id')
                  ->orWhereHas('timetableDetails', function ($sub) {
                      $sub->whereIn('type', ['exam', 'ujian']);
                  })
                  ->orWhereHas('timetableModule');
            })
            ->where(function ($q) {
                $q->whereDoesntHave('timetableDetails')
                  ->orWhereHas('timetableDetails', function ($sub) {
                      $sub->whereIn('type', ['exam', 'ujian']);
                  });
            });
        }

        $viewName = config('app.new_template', false)
            ? 'livewire.admin.exam.timetable.admin-exam-timetable-index-new'
            : 'livewire.admin.exam.timetable.admin-exam-timetable-index';

        $layoutName = config('app.new_template', false)
            ? 'layout.app-horizontal'
            : 'layout.app';

        return view($viewName, [
            'timetables' => $timetables->paginate($this->perPage),
            'availableSupervisors' => $this->availableSupervisors ?? [],
        ])
            ->extends($layoutName)
            ->section('content');
    }
}
