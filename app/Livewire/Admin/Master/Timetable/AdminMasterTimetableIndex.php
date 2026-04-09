<?php

namespace App\Livewire\Admin\Master\Timetable;

use App\Helpers\AlertHelper;
use App\Models\Exam\ExamLiveSession;
use App\Models\Exam\ExamRecording;
use App\Models\Classmate\Classmate;
use App\Models\Master\Exam\ExamRoom;
use App\Models\Master\Exam\ExamSession;
use App\Models\Master\Question\Module;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Master\Timetable\Timetable;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\User\UserTimetable;
use App\Models\Study\Study;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Session;
use Carbon\Carbon;
use App\Services\Exam\RecordingFinalizer;

class AdminMasterTimetableIndex extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public $search = '';
    public $perPage = 5;
    public $data_id;
    public $name;
    public $module_id;
    public $supervisors = [];
    public $start_time;
    public $end_time;
    public $description;
    public $getSupervisors = [];
    public $modules = [];
    public $studys = [];
    public $study_id;
    public $classmates = [];
    public $classmate_id;
    public $examRooms = [];
    public $exam_room_id;
    public $examSessions = [];
    public $exam_session_id;
    public $require_seb = false;
    public $is_recording = false;
    public $is_streaming = false;

    public function mount()
    {
        Session::forget('timetable_id');
        $this->modules = Module::whereNotNull('category_question_settings')
            ->whereRaw("category_question_settings <> '{}'::jsonb")
            ->pluck('name', 'id')
            ->toArray();
        $this->getSupervisors = User::companyRole('Pengawas', Auth::user()->company_id)->select('name', 'id')->get()->pluck('name', 'id')->toArray();
        $this->classmates = Classmate::where('company_id', Auth::user()->company_id)->pluck('name', 'id')->toArray();
        $this->examRooms = ExamRoom::where('company_id', Auth::user()->company_id)->get();
        $this->examSessions = ExamSession::where('company_id', Auth::user()->company_id)->get();
    }

    public function openModal()
    {
        $this->is_recording = true;
        $this->is_streaming = true;
        return $this->dispatch('open-modal', ['id' => 'modal-timetable']);
    }

    public function updatedModuleId()
    {
        if ($this->module_id) {
            $modules = Module::with('moduleQuestions')->find($this->module_id);

            $this->studys = Study::select('name', 'id')->whereIn('id', json_decode($modules->studys))->get()->pluck('name', 'id')->toArray();
        } else {
            $this->studys = [];
        }
    }

    public function closeModal()
    {
        $this->reset([
            'data_id',
            'name',
            'module_id',
            'supervisors',
            'start_time',
            'end_time',
            'description',
            'studys',
            'classmate_id',
            'exam_room_id',
            'exam_session_id',
            'require_seb',
            'is_recording',
            'is_streaming'
        ]);
        return $this->dispatch('close-modal', ['id' => 'modal-timetable']);
    }

    public function edit($id)
    {
        $data = Timetable::find($id);

        $this->data_id = $data->id;
        $this->name = $data->name;
        $this->module_id = $data->module_id;
        $this->exam_room_id = $data->exam_room_id;
        $this->exam_session_id = $data->exam_session_id;
        $this->classmate_id = $data->classmate_id;
        $this->supervisors = json_decode($data->supervisors, true) ?? [];
        $this->start_time = Carbon::parse($data->start_time)->format('Y-m-d\TH:i');
        $this->end_time = Carbon::parse($data->end_time)->format('Y-m-d\TH:i');
        $this->description = $data->description;
        $this->require_seb = $data->require_seb ?? false;
        $this->is_recording = $data->is_recording ?? false;
        $this->is_streaming = $data->is_streaming ?? false;

        // Pastikan hasil decode adalah array
        $rawStudys = $data->studys;

        // decode pertama
        $firstDecode = json_decode($rawStudys, true);

        // kalau hasilnya masih string, berarti perlu decode lagi
        if (is_string($firstDecode)) {
            $studysIds = json_decode($firstDecode, true) ?: [];
        } else {
            $studysIds = $firstDecode ?: [];
        }

        $this->studys = Study::select('name', 'id')
            ->whereIn('id', $studysIds)
            ->pluck('name', 'id')
            ->toArray();

        $this->openModal();
    }

    public function confirmGenerateToken($id)
    {
        return AlertHelper::confirmWarning('generateToken', 'Apakah Anda Yakin Membuat Token?', $id);
    }

    public function generateToken($id)
    {
        try {
            DB::beginTransaction();
            $token = '';
            $codeAlphabet = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
            // $codeAlphabet .= 'abcdefghijklmnopqrstuvwxyz';
            $codeAlphabet .= '0123456789';
            $max = strlen($codeAlphabet); // edited
            for ($i = 0; $i < 6; $i++) {
                $token .= $codeAlphabet[random_int(0, $max - 1)];
            }

            Timetable::where('id', $id[0])->update([
                'code' => trim($token),
            ]);
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollback();
            AlertHelper::error('Gagal', 'Token gagal dibuat!');
            return Log::info('Gagal Menghapus Token : ' . $th);
        }
        AlertHelper::success('Berhasil', 'Token berhasil dibuat!');
    }

    public function confirmDelete($id)
    {
        return AlertHelper::confirmDelete('delete', 'Anda yakin ingin menghapus data ini?', $id);
    }

    public function correctIndex($id)
    {
        return redirect()->route('admin.master.timetable.correct', $id);
    }

    public function delete($id)
    {
        try {
            DB::beginTransaction();
            $data = Timetable::find($id[0]);
            $data->delete();
            DB::commit();
            AlertHelper::success('Berhasil', 'Data berhasil dihapus!');
            $this->closeModal();
        } catch (\Throwable $th) {
            DB::rollback();
            AlertHelper::error('Gagal', 'Data gagal dihapus!');
            return Log::info('Gagal Menghapus Data Jadwal : ' . $th);
        }
    }

    protected $rules = [
        'start_time' => 'required|date',
        'end_time' => 'required|date|after:start_time',
    ];

    protected $messages = [
        'start_time.required' => 'Waktu Mulai wajib diisi',
        'end_time.required' => 'Waktu Selesai wajib diisi',
        'end_time.after' => 'Waktu Selesai harus lebih besar dari Waktu Mulai',
    ];


    public function updatedStartTime($value)
    {
        $this->start_time = Carbon::parse($value)->format('Y-m-d H:i:s');
        $this->end_time = Carbon::parse($this->start_time)->addHour(2)->format('Y-m-d H:i:s');
        $this->validateOnly('start_time');
        $this->validateOnly('end_time');
    }

    public function updatedEndTime($value)
    {
        $this->end_time = Carbon::parse($value)->format('Y-m-d H:i:s');
        $this->validateOnly('end_time');
        $this->validateOnly('start_time');
    }

    public function submit()
    {
        $this->validate([
            'name' => 'required',
            'module_id' => 'required',
            'supervisors' => 'required',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
            'classmate_id' => 'required',
            'exam_room_id' => 'required',
            'exam_session_id' => 'required',
        ], [
            'classmate_id.required' => 'Peserta wajib diisi',
            'name.required' => 'Nama Jadwal wajib diisi',
            'module_id.required' => 'Modul wajib diisi',
            'exam_room_id.required' => 'Ruang ujian wajib diisi',
            'exam_session_id.required' => 'Sesi ujian wajib diisi',
            'supervisors.required' => 'Pengawas wajib diisi',
            'start_time.required' => 'Waktu Mulai wajib diisi',
            'end_time.required' => 'Waktu Selesai wajib diisi',

            // VALIDASI PENTING BARU
            'end_time.after' => 'Waktu Selesai harus lebih besar dari Waktu Mulai',
        ]);

        try {
            DB::beginTransaction();

            Timetable::updateOrCreate([
                'id' => $this->data_id,
            ], [
                'company_id' => Auth::user()->company_id,
                'classmate_id' => $this->classmate_id,
                'name' => $this->name,
                'module_id' => $this->module_id,
                'exam_room_id' => $this->exam_room_id,
                'exam_session_id' => $this->exam_session_id,
                'supervisors' => json_encode($this->supervisors),
                'start_time' => $this->start_time,
                'end_time' => $this->end_time,
                'description' => $this->description,
                'studys' => $this->studys ? json_encode(array_keys($this->studys)) : null,
                'require_seb' => $this->require_seb ?? false,
                'is_recording' => $this->is_recording ?? false,
                'is_streaming' => $this->is_streaming ?? false,
            ]);

            DB::commit();
            AlertHelper::success('Berhasil', 'Data berhasil disimpan!');
            $this->closeModal();

        } catch (\Throwable $th) {

            DB::rollback();
            AlertHelper::error('Gagal', 'Data gagal disimpan!' . $th->getMessage());
            return Log::error('Gagal Menyimpan Data Jadwal : ' . $th);
        }
    }

    public function toggleRecording($id)
    {
        try {
            $timetable = Timetable::findOrFail($id);
            $timetable->update([
                'is_recording' => !$timetable->is_recording,
            ]);
        } catch (\Throwable $th) {
            AlertHelper::error('Gagal', 'Gagal mengubah status recording.');
            Log::error('Gagal toggle recording', [
                'error' => $th->getMessage(),
                'trace' => $th->getTraceAsString(),
            ]);
        }
    }

    public function toggleStreaming($id)
    {
        try {
            $timetable = Timetable::findOrFail($id);
            $timetable->update([
                'is_streaming' => !$timetable->is_streaming,
            ]);
        } catch (\Throwable $th) {
            AlertHelper::error('Gagal', 'Gagal mengubah status streaming.');
            Log::error('Gagal toggle streaming', [
                'error' => $th->getMessage(),
                'trace' => $th->getTraceAsString(),
            ]);
        }
    }


    public function confirmDetail($id)
    {
        return redirect()->route('admin.master.timetable.detail', ['timetable_id' => $id]);
    }

    public function confirmVideo($id)
    {
        return redirect()->route('admin.master.timetable.video', ['timetable_id' => $id]);
    }

    public function confirmAlert($id)
    {
        return redirect()->route('admin.master.timetable.alert', ['timetable_id' => $id]);
    }

    public function sessionIndex($id)
    {
        return redirect()->route('admin.master.timetable.session', ['timetable_id' => $id]);
    }

    public function liveSession($id)
    {
        return redirect()->route('admin.master.timetable.streaming', ['timetable_id' => $id]);
    }

    public function render()
    {
        $timetable = Timetable::query()
            ->when($this->search, function ($query, $search) {
                $query->where('name', 'ilike', '%' . $search . '%')
                    ->orWhere('start_time', 'ilike', '%' . $search . '%')
                    ->orWhere('end_time', 'ilike', '%' . $search . '%')
                    ->orWhere('description', 'ilike', '%' . $search . '%');
            })
            ->orderBy('order', 'desc')
            ->paginate($this->perPage);

        return view('livewire.admin.master.timetable.admin-master-timetable-index', [
            'timetables' => $timetable,
        ])
            ->extends('layout.app')
            ->section('content')
        ;
    }

    public function confirmSuspend($id)
    {
        return AlertHelper::confirmWarning('suspendTimetable', 'Apakah Anda Yakin Mensuspend Sesi Ujian?', $id);
    }

    public function suspendTimetable($id)
    {
        try {
            DB::beginTransaction();
            $timetableId = is_array($id) ? ($id[0] ?? null) : $id;
            if (!$timetableId) {
                throw new \InvalidArgumentException('ID Jadwal tidak valid.');
            }

            // Nonaktifkan semua live session aktif untuk jadwal ini
            $sessions = ExamLiveSession::where('timetable_id', $timetableId)
                ->where('is_active', true)
                ->get();
            foreach ($sessions as $session) {
                $session->update([
                    'is_active' => false,
                    'connection_status' => 'disconnected',
                ]);
            }

            // Tandai semua user_timetables yang sedang ujian menjadi suspend
            $userTimetables = UserTimetable::where('timetable_id', $timetableId)
                ->whereIn('status', ['exam', 'warning'])
                ->get();

            foreach ($userTimetables as $ut) {
                $ut->update([
                    'status' => 'suspend',
                    'end_exam' => Carbon::now(),
                ]);

                // Finalisasi rekaman chunk untuk masing-masing peserta
                $result = RecordingFinalizer::finalizeForUserTimetable($ut->id);

                // Update rekaman terakhir peserta dengan hasil finalisasi
                $latestRecording = ExamRecording::where('user_timetable_id', $ut->id)
                    ->orderBy('created_at', 'desc')
                    ->first();
                if ($latestRecording) {
                    $latestRecording->update([
                        'video_path' => $result['merged_video'] ?: $result['manifest'],
                        'file_size' => $result['total_size'],
                        'end_time' => Carbon::now(),
                        'status' => 'completed',
                    ]);
                }
            }

            DB::commit();
            AlertHelper::success('Berhasil', 'Sesi ujian berhasil di-suspend dan rekaman tersimpan.');
        } catch (\Throwable $th) {
            DB::rollBack();
            AlertHelper::error('Gagal', 'Suspend sesi ujian gagal dilakukan.');
            Log::error('Gagal suspend jadwal', [
                'error' => $th->getMessage(),
                'trace' => $th->getTraceAsString(),
            ]);
        }
    }

    public function printCard($id)
    {
        try {
            $timetable = Timetable::with([
                'classmate' => function ($q) {
                    $q->with(['classmateStudents.user.userDetail']);
                }
            ])->findOrFail($id);

            $company = Auth::user()->company()->with('companyDetail')->first();

            $pdf = Pdf::loadView('livewire.admin.master.timetable.admin-master-timetable-card-pdf', [
                'timetable' => $timetable,
                'company' => $company,
            ])->setPaper('a4', 'portrait');

            return response()->streamDownload(
                fn() => print ($pdf->output()),
                'kartu-peserta-' . \Str::slug($timetable->name) . '.pdf'
            );

        } catch (\Throwable $th) {
            AlertHelper::error('Gagal', 'Gagal mencetak kartu peserta.');
            Log::error('Gagal cetak kartu peserta', [
                'error' => $th->getMessage(),
                'trace' => $th->getTraceAsString(),
            ]);
        }
    }
}
