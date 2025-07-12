<?php

namespace App\Livewire\Admin\Exam\Timetable;

use App\Helpers\AlertHelper;
use App\Models\Master\Question\ModuleQuestion;
use App\Models\Master\Timetable\Timetable;
use App\Models\User;
use App\Models\User\UserModuleQuestion;
use App\Models\User\UserTimetable;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Auth;
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
                AlertHelper::error('Gagal', 'Token Yang Dimasukkan Tidak Sesuai');
                return;
            }

            $modulesQuestions = $timeTable->module->random_question ? ModuleQuestion::withoutGlobalScope('user_scope')->select('id')->where('module_id', $timeTable->module->id)->inRandomOrder()->get() : ModuleQuestion::withoutGlobalScope('user_scope')->select('id')->where('module_id', $timeTable->module->id)->orderBy('order', 'asc')->get();

            $UserTimetable = UserTimetable::create([
                'user_id' => Auth::id(),
                'timetable_id' => $timeTable->id,
                'start_process' => Carbon::now(),
            ]);

            foreach ($modulesQuestions as $moduleQuestion) {
                UserModuleQuestion::create([
                    'user_timetable_id' => $UserTimetable->id,
                    'module_question_id' => $moduleQuestion->id,
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

    public function hydrate()
    {
        $this->resetPage();
    }

    public function confirmBackExam($id)
    {
        $userTimetable = UserTimetable::find($id);
        if (!$userTimetable) {
            return AlertHelper::error('Gagal', 'Data Ujian Tidak Ditemukan');
        }

        if ($userTimetable->status == 'done') {
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
        // dd(Auth::id());

        $userTimetableStatusDone = UserTimetable::query()
            ->where('user_id', Auth::id())
            ->where('status', 'done')
            ->get()
            ->pluck('timetable_id')
            ->toArray();

        $userTimetableStatus = UserTimetable::query()
            ->where('user_id', Auth::id())
            ->whereNotIn('status', ['done'])
            ->get()
            ->pluck('timetable_id')
            ->toArray();

        $timetables = Timetable::query()
            ->when($this->search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', '%' . $search . '%')
                        ->orWhere('description', 'like', '%' . $search . '%');
                });
            })
            ->where(function ($query) {
                $now = Carbon::now();
                $query->where('start_time', '<=', $now->copy()->addMinutes(5))
                    ->where('end_time', '>=', $now->copy()->subMinutes(5));
            });

        if (!empty($userTimetableStatusDone)) {
            $timetables->whereNotIn('id', $userTimetableStatusDone);
        }

        if (!empty($userTimetableStatus)) {
            $timetables->whereIn('id', $userTimetableStatus);
        }

        return view('livewire.admin.exam.timetable.admin-exam-timetable-index', [
            'timetables' => $timetables->paginate($this->perPage),
        ])
            ->extends('layout.app')
            ->section('content');
    }
}
