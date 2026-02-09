<?php

namespace App\Livewire\Admin\Exam\Timetable;

use App\Helpers\AlertHelper;
use App\Models\Master\Question\ModuleQuestion;
use App\Models\Master\Timetable\Timetable;
use App\Models\Timetable\TimetableQuestion;
use App\Models\User;
use App\Models\User\UserModuleQuestion;
use App\Models\User\UserTimetable;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
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
        return $this->dispatch('open-modal', ['id' => 'modal-start-exam']);
    }

    public function closeModalStartExam()
    {
        $this->reset(['data_id', 'code']);
        return $this->dispatch('close-modal', ['id' => 'modal-start-exam']);
    }

    public function submitStartExam()
    {
        $this->validate([
            'code' => 'required',
        ], [
            'code.required' => 'Kode Ujian Wajib Diisi',
        ]);

        try {
            DB::begintransaction();
            $timeTable = Timetable::where('code', $this->code)
                ->where('company_id', Auth::user()->company_id)
                ->find($this->data_id);

            if (!$timeTable) {
                AlertHelper::error('Gagal', 'Token Yang Dimasukan Tidak Sesuai');
                return;
            }

            $transactionModule = $timeTable->timetableModule;

            $categorySettings = $transactionModule?->module?->category_question_settings ?? [];
            if (is_string($categorySettings)) {
                $categorySettings = json_decode($categorySettings, true) ?? [];
            }

            if (!empty($categorySettings)) {
                $modulesQuestions = collect();

                foreach ($categorySettings as $categoryId => $settings) {
                    foreach (['default', 'easy', 'medium', 'hard'] as $difficulty) {
                        $take = (int) ($settings[$difficulty] ?? 0);
                        if ($take <= 0) {
                            continue;
                        }

                        $query = TimetableQuestion::withoutGlobalScope('user_scope')
                            ->select('id', 'study_id')
                            ->where('timetable_module_id', $transactionModule->id)
                            ->where('category_question_id', $categoryId);

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
                            $query->orderBy('id');
                        }

                        $modulesQuestions = $modulesQuestions->merge($query->limit($take)->get());
                    }
                }
            } else {
                $query = TimetableQuestion::withoutGlobalScope('user_scope')
                    ->select('id', 'study_id')
                    ->where('timetable_module_id', $transactionModule->id);

                if ($transactionModule->random_question) {
                    $query->inRandomOrder();
                } else {
                    $query->orderBy('id');
                }

                $modulesQuestions = $query->get();
            }

            $UserTimetable = UserTimetable::create([
                'user_id' => Auth::id(),
                'timetable_id' => $timeTable->id,
                'start_process' => Carbon::now(),
                'studys' => $timeTable->studys,
                'is_recording' => $timeTable?->is_recording ?? false,
                'is_streaming' => $timeTable?->is_streaming ?? false,
            ]);

            foreach ($modulesQuestions as $moduleQuestion) {
                UserModuleQuestion::create([
                    'user_timetable_id' => $UserTimetable->id,
                    'timetable_module_id' => $transactionModule->id,
                    'timetable_question_id' => $moduleQuestion->id,
                    'study_id' => $moduleQuestion->study_id,
                ]);
            }

            DB::commit();
            session()->flash('saved', [
                'title' => 'Ujian Telah Dimulai!',
                'text' => 'Anda berhasil memulai ujian!',
            ]);
            return redirect()->route('admin.exam.warning');
        } catch (\Throwable $th) {
            DB::rollback();
            AlertHelper::error('Gagal' . $th->getMessage());
            return Log::error($th->getMessage());
        }
    }

    // public function hydrate()
    // {
    //     $this->resetPage();
    // }

    public function confirmBackExam($id)
    {
        $userTimetable = UserTimetable::find($id);
        if (!$userTimetable) {
            return AlertHelper::error('Gagal', 'Data Ujian Tidak Ditemukan');
        }

        if (in_array($userTimetable->status, ['done', 'suspend'])) {
            return AlertHelper::error('Gagal', 'Ujian Sudah Selesai');
        }

        if ($userTimetable->status == 'warning') {
            Session::put('user_timetable_id', $id);
            return redirect()->route('admin.exam.warning');
        }

        if ($userTimetable->status == 'exam') {
            Session::put('user_timetable_id', $id);
            return redirect()->route('admin.exam.detail');
        }
    }

    public function render()
    {
        $userTimetableStatusDone = UserTimetable::query()
            ->where('user_id', Auth::id())
            ->whereIn('status', ['done', 'suspend'])
            ->get()
            ->pluck('timetable_id')
            ->toArray();

        $auth = Auth::user();

        $timetables = Timetable::query()
            // ->whereNotNull('code')
            ->when($this->search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'ilike', '%' . $search . '%')
                        ->orWhere('description', 'ilike', '%' . $search . '%');
                });
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
        //             ->orWhere('studys', 'ILIKE', '%\\\"' . $auth->study_id . '\\\"%');
        //     });
        // }


        if ($auth->hasRole(['Mahasiswa'])) {
            // $timetables->where('study_id', $auth->study_id);
            if ($auth->classmateStudent) {
                $timetables->where('classmate_id', $auth->classmateStudent->classmate_id);
            } else {
                $timetables->whereNull('classmate_id');
            }
        }

        if (!empty($userTimetableStatusDone)) {
            $timetables->whereNotIn('id', $userTimetableStatusDone);
        }

        return view('livewire.admin.exam.timetable.admin-exam-timetable-index', [
            'timetables' => $timetables->paginate($this->perPage),
        ])
            ->extends('layout.app')
            ->section('content');
    }
}
