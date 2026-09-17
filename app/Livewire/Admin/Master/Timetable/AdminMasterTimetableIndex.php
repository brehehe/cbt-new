<?php

namespace App\Livewire\Admin\Master\Timetable;

use App\Helpers\AlertHelper;
use App\Models\Classmate\Classmate;
use App\Models\Exam\ExamLiveSession;
use App\Models\Exam\ExamRecording;
use App\Models\Master\DigitalBook\DigitalBook;
use App\Models\Master\Exam\ExamRoom;
use App\Models\Master\Exam\ExamSession;
use App\Models\Master\Question\Module;
use App\Models\Master\Timetable\Timetable;
use App\Models\Master\Timetable\TimetableAttendance;
use App\Models\Master\Timetable\TimetableDetail;
use App\Models\Study\Study;
use App\Models\User;
use App\Models\User\UserTimetable;
use App\Services\Exam\RecordingFinalizer;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithPagination;
use Milon\Barcode\DNS2D;
use Session;

class AdminMasterTimetableIndex extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $search = '';

    public $perPage = 5;

    public $data_id;

    public $modules;

    public $filter_simulation = 'all';

    public $openModal = false;

    public $name;

    public $code;

    public $classmate_id;

    public $exam_room_id;

    public $exam_session_id;

    public $module_id;

    public $supervisors = [];

    public $total_questions;

    public $require_seb = false;

    public $start_time;

    public $end_time;

    public $description;

    public $is_simulation = 'false';

    public $is_camera = false;

    public $is_recording = false;

    public $is_streaming = false;

    public $allow_repeat = false;

    public $require_token = true;

    public $token;

    public $classmates = [];

    public $exam_rooms = [];

    public $exam_sessions = [];

    public $examRooms = [];

    public $examSessions = [];

    public $extra_time;

    public $getSupervisors = [];

    // Filter properties
    public $filter_room = 'all';

    public $filter_session = 'all';

    public $selected_study_program;

    // Study relations
    public $studys;

    public $selectedStudys = [];

    // Dynamic Schedules Repeater (Multiple Schedules)
    public $schedules = [];

    // Supervisor modal properties
    public $timetable_id_supervisor;
    public $selectedSupervisors = [];
    public $availableSupervisors = [];

    // LEMES Mode Properties
    public $digitalBooks = [];
    public $timetable_details = [];

    // LEMES Attendance Scanner Properties
    public $scan_timetable_id;
    public $scan_detail_id;
    public $selected_scanner_timetable;
    public $recentScanned = [];
    public $attendanceSummary = [];
    public $openAttendanceModal = false;
    public $openAttendanceRecapModal = false;

    // LEMES QRCODE Print Properties
    public $selected_qr_detail = null;
    public $selected_qr_base64 = null;
    public $selected_qr_timetable = null;

    public function mount()
    {
        Session::forget('timetable_id');
        $this->modules = Module::whereNotNull('category_question_settings')
            ->where(function ($query) {
                if (DB::getDriverName() === 'pgsql') {
                    $query->whereRaw("category_question_settings <> '{}'::jsonb");
                } else {
                    $query->whereRaw("category_question_settings <> '{}'");
                }
            })
            ->pluck('name', 'id')
            ->toArray();
        $this->getSupervisors = User::companyRole('Pengawas', Auth::user()->company_id)->select('name', 'id')->get()->pluck('name', 'id')->toArray();
        $this->classmates = Classmate::where('company_id', Auth::user()->company_id)->pluck('name', 'id')->toArray();
        $this->examRooms = ExamRoom::where('company_id', Auth::user()->company_id)->get();
        $this->examSessions = ExamSession::where('company_id', Auth::user()->company_id)->get();

        if (is_lemes()) {
            $this->digitalBooks = DigitalBook::select('id', 'title', 'content_type')->get();
        }
    }

    public function createNewDetailState()
    {
        $tokenAlphabet = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $token = '';
        for ($i = 0; $i < 6; $i++) {
            $token .= $tokenAlphabet[random_int(0, strlen($tokenAlphabet) - 1)];
        }

        $defaultRoomId = $this->examRooms->first()?->id ?? '';
        $defaultSessionId = $this->examSessions->first()?->id ?? '';

        return [
            'id' => null,
            'code' => 'TTD-' . date('Ymd') . '-' . strtoupper(Str::random(6)),
            'exam_room_id' => $defaultRoomId,
            'exam_session_id' => $defaultSessionId,
            'supervisors' => [],
            'exam_date' => date('Y-m-d'),
            'start_time' => Carbon::now()->setTime(8, 0)->format('Y-m-d\TH:i'),
            'end_time' => Carbon::now()->setTime(10, 0)->format('Y-m-d\TH:i'),
            'type' => 'exam', // 'exam' or 'material'
            'module_id' => '',
            'exam_type' => 'resmi',
            'allow_repeat' => false,
            'require_token' => true,
            'token' => $token,
            'is_camera' => false,
            'is_recording' => false,
            'is_streaming' => false,
            'digital_book_id' => '',
            'require_attendance' => true,
        ];
    }

    public function addDetailRow()
    {
        $this->timetable_details[] = $this->createNewDetailState();
    }

    public function removeDetailRow($index)
    {
        if (count($this->timetable_details) <= 1) {
            return AlertHelper::error('Gagal', 'Minimal harus ada 1 detail jadwal.');
        }
        unset($this->timetable_details[$index]);
        $this->timetable_details = array_values($this->timetable_details);
    }

    public function generateDetailToken($index)
    {
        $tokenAlphabet = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $token = '';
        for ($i = 0; $i < 6; $i++) {
            $token .= $tokenAlphabet[random_int(0, strlen($tokenAlphabet) - 1)];
        }
        $this->timetable_details[$index]['token'] = $token;
    }

    public function openCreateModal()
    {
        $this->reset([
            'data_id',
            'name',
            'module_id',
            'total_questions',
            'supervisors',
            'start_time',
            'end_time',
            'description',
            'studys',
            'classmate_id',
            'exam_room_id',
            'exam_session_id',
            'require_seb',
            'is_camera',
            'is_recording',
            'is_streaming',
            'is_simulation',
            'allow_repeat',
            'require_token',
            'timetable_details',
        ]);
        $this->is_simulation = 'false';
        $this->allow_repeat = false;
        $this->require_token = true;

        if (is_lemes()) {
            $this->timetable_details = [
                $this->createNewDetailState()
            ];
        } else {
            $this->timetable_details = [];
        }
        
        $company = Auth::user()->company;
        $this->is_camera = $company ? (bool)$company->enable_camera : true;
        $this->is_recording = $company ? (bool)$company->enable_recording : true;
        $this->is_streaming = $company ? (bool)$company->enable_streaming : true;

        return $this->dispatch('open-modal', ['id' => 'modal-timetable']);
    }

    public function openModal()
    {
        return $this->dispatch('open-modal', ['id' => 'modal-timetable']);
    }

    public function updatedIsCamera($value)
    {
        if (!$value) {
            $this->is_recording = false;
            $this->is_streaming = false;
        }
    }

    public function updatedClassmateId()
    {
        Log::info('updatedClassmateId called:', [
            'classmate_id' => $this->classmate_id,
        ]);
        if ($this->classmate_id) {
            $classmate = Classmate::find($this->classmate_id);
            if ($classmate) {
                Log::info('classmate found:', [
                    'id' => $classmate->id,
                    'name' => $classmate->name,
                    'exam_room_id' => $classmate->exam_room_id,
                    'exam_session_id' => $classmate->exam_session_id,
                    'exam_date' => $classmate->exam_date,
                ]);
                if ($classmate->exam_room_id) {
                    $this->exam_room_id = $classmate->exam_room_id;
                }
                if ($classmate->exam_session_id) {
                    $this->exam_session_id = $classmate->exam_session_id;
                }
                if ($classmate->exam_date) {
                    $this->start_time = Carbon::parse($classmate->exam_date)->setTime(8, 0)->format('Y-m-d\TH:i');
                    $this->end_time = Carbon::parse($classmate->exam_date)->setTime(10, 0)->format('Y-m-d\TH:i');
                }
                Log::info('properties prefilled:', [
                    'exam_room_id' => $this->exam_room_id,
                    'exam_session_id' => $this->exam_session_id,
                    'start_time' => $this->start_time,
                    'end_time' => $this->end_time,
                ]);
            }
        }
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
        $this->resetValidation();
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
            'is_camera',
            'is_recording',
            'is_streaming',
            'is_simulation',
            'allow_repeat',
            'require_token',
            'timetable_details',
        ]);

        return $this->dispatch('close-modal', ['id' => 'modal-timetable']);
    }

    public function edit($id)
    {
        $data = Timetable::with('timetableDetails')->find($id);

        $this->data_id = $data->id;
        $this->name = $data->name;
        $this->module_id = $data->module_id;
        $this->total_questions = $data->total_questions;
        $this->exam_room_id = $data->exam_room_id;
        $this->exam_session_id = $data->exam_session_id;
        $this->classmate_id = $data->classmate_id;
        $supervisorsData = $data->supervisors;
        if (is_array($supervisorsData)) {
            $this->supervisors = $supervisorsData;
        } else {
            $this->supervisors = json_decode($supervisorsData, true) ?: [];
        }
        $this->start_time = Carbon::parse($data->start_time)->format('Y-m-d\TH:i');
        $this->end_time = Carbon::parse($data->end_time)->format('Y-m-d\TH:i');
        $this->description = $data->description;
        $this->require_seb = $data->require_seb ?? false;
        $this->is_camera = $data?->is_camera ? true : false;
        $this->is_recording = $data?->is_recording ? true : false;
        $this->is_streaming = $data?->is_streaming ? true : false;
        $this->is_simulation = $data?->is_simulation ?? 'false';
        $this->allow_repeat = (bool)($data?->allow_repeat ?? false);
        $this->require_token = (bool)($data?->require_token ?? true);

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

        if (is_lemes()) {
            $details = $data->timetableDetails;
            if ($details && $details->isNotEmpty()) {
                $this->timetable_details = $details->map(function ($det) {
                    return [
                        'id' => $det->id,
                        'code' => $det->code,
                        'exam_room_id' => $det->exam_room_id,
                        'exam_session_id' => $det->exam_session_id,
                        'supervisors' => is_array($det->supervisors) ? $det->supervisors : (json_decode($det->supervisors ?? '[]', true) ?: []),
                        'exam_date' => $det->exam_date ? Carbon::parse($det->exam_date)->format('Y-m-d') : '',
                        'start_time' => $det->start_time ? Carbon::parse($det->start_time)->format('Y-m-d\TH:i') : '',
                        'end_time' => $det->end_time ? Carbon::parse($det->end_time)->format('Y-m-d\TH:i') : '',
                        'type' => $det->type ?? 'exam',
                        'module_id' => $det->module_id,
                        'exam_type' => $det->exam_type ?? 'resmi',
                        'allow_repeat' => (bool)$det->allow_repeat,
                        'require_token' => (bool)$det->require_token,
                        'token' => $det->token,
                        'is_camera' => (bool)$det->is_camera,
                        'is_recording' => (bool)$det->is_recording,
                        'is_streaming' => (bool)$det->is_streaming,
                        'digital_book_id' => $det->digital_book_id,
                        'require_attendance' => (bool)$det->require_attendance,
                    ];
                })->toArray();
            } else {
                $this->timetable_details = [$this->createNewDetailState()];
            }
        }

        $this->openModal();
    }

    public function canGenerateToken()
    {
        $user = Auth::user();
        if (!$user) {
            return false;
        }

        $company = $user->company;
        if ($company && $company->only_admin_generate_token) {
            return $user->hasRole('Admin');
        }

        return true;
    }

    public function confirmGenerateToken($id)
    {
        if (!$this->canGenerateToken()) {
            AlertHelper::error('Akses Ditolak', 'Hanya Admin yang dapat membuat token!');
            return;
        }
        return AlertHelper::confirmWarning('generateToken', 'Apakah Anda Yakin Membuat Token?', $id);
    }

    public function generateToken($id)
    {
        if (!$this->canGenerateToken()) {
            AlertHelper::error('Akses Ditolak', 'Hanya Admin yang dapat membuat token!');
            return;
        }
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

            return Log::info('Gagal Menghapus Token : '.$th);
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

            return Log::info('Gagal Menghapus Data Jadwal : '.$th);
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
        if (is_lemes()) {
            $this->validate([
                'name' => 'required',
                'classmate_id' => 'required',
                'timetable_details' => 'required|array|min:1',
                'timetable_details.*.exam_room_id' => 'required',
                'timetable_details.*.exam_session_id' => 'required',
                'timetable_details.*.start_time' => 'required',
                'timetable_details.*.end_time' => 'required|after:timetable_details.*.start_time',
            ], [
                'name.required' => 'Nama Jadwal wajib diisi',
                'classmate_id.required' => 'Peserta kelas wajib dipilih',
                'timetable_details.min' => 'Minimal harus ada 1 detail jadwal',
                'timetable_details.*.exam_room_id.required' => 'Ruang ujian di baris detail wajib dipilih',
                'timetable_details.*.exam_session_id.required' => 'Sesi ujian di baris detail wajib dipilih',
                'timetable_details.*.start_time.required' => 'Waktu Mulai di baris detail wajib diisi',
                'timetable_details.*.end_time.required' => 'Waktu Selesai di baris detail wajib diisi',
                'timetable_details.*.end_time.after' => 'Waktu Selesai harus lebih besar dari Waktu Mulai',
            ]);

            // Validate type specific fields
            foreach ($this->timetable_details as $dIdx => $dRow) {
                $isMat = in_array($dRow['type'] ?? 'exam', ['material', 'materi']);
                if (!$isMat && empty($dRow['module_id'])) {
                    return AlertHelper::error('Gagal', 'Modul soal pada baris detail ' . ($dIdx + 1) . ' wajib dipilih untuk tipe Ujian.');
                }
                if ($isMat && empty($dRow['digital_book_id'])) {
                    return AlertHelper::error('Gagal', 'Materi/Buku Digital pada baris detail ' . ($dIdx + 1) . ' wajib dipilih untuk tipe Materi.');
                }
            }

            try {
                DB::beginTransaction();

                $firstDetail = $this->timetable_details[0] ?? [];
                $firstModuleId = null;
                foreach ($this->timetable_details as $det) {
                    $isMat = in_array($det['type'] ?? 'exam', ['material', 'materi']);
                    if (!$isMat && !empty($det['module_id'])) {
                        $firstModuleId = $det['module_id'];
                        break;
                    }
                }

                $timetable = Timetable::updateOrCreate([
                    'id' => $this->data_id,
                ], [
                    'company_id' => Auth::user()->company_id,
                    'classmate_id' => $this->classmate_id,
                    'name' => $this->name,
                    'module_id' => $firstModuleId,
                    'exam_room_id' => $firstDetail['exam_room_id'] ?? null,
                    'exam_session_id' => $firstDetail['exam_session_id'] ?? null,
                    'supervisors' => is_array($firstDetail['supervisors'] ?? null) ? $firstDetail['supervisors'] : (json_decode($firstDetail['supervisors'] ?? '[]', true) ?: []),
                    'start_time' => $firstDetail['start_time'] ?? now(),
                    'end_time' => $firstDetail['end_time'] ?? now()->addHours(2),
                    'description' => $this->description,
                    'is_camera' => $firstDetail['is_camera'] ?? false,
                    'is_recording' => $firstDetail['is_recording'] ?? false,
                    'is_streaming' => $firstDetail['is_streaming'] ?? false,
                    'allow_repeat' => (bool)($firstDetail['allow_repeat'] ?? false),
                    'require_token' => (bool)($firstDetail['require_token'] ?? true),
                    'code' => $firstDetail['token'] ?? null,
                ]);

                // Sync Timetable Details
                $existingDetailIds = [];
                foreach ($this->timetable_details as $order => $det) {
                    $token = $det['token'] ?? null;
                    if (empty($token) && !empty($det['require_token'])) {
                        $tokenAlphabet = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
                        $token = '';
                        for ($i = 0; $i < 6; $i++) {
                            $token .= $tokenAlphabet[random_int(0, strlen($tokenAlphabet) - 1)];
                        }
                    }

                    $isMat = in_array($det['type'] ?? 'exam', ['material', 'materi']);

                    $detailModel = TimetableDetail::updateOrCreate(
                        [
                            'id' => !empty($det['id']) ? $det['id'] : (string) Str::uuid(),
                        ],
                        [
                            'timetable_id' => $timetable->id,
                            'code' => !empty($det['code']) ? $det['code'] : ('TTD-' . date('Ymd') . '-' . strtoupper(Str::random(6))),
                            'exam_room_id' => $det['exam_room_id'] ?: null,
                            'exam_session_id' => $det['exam_session_id'] ?: null,
                            'supervisors' => is_array($det['supervisors'] ?? null) ? $det['supervisors'] : (json_decode($det['supervisors'] ?? '[]', true) ?: []),
                            'exam_date' => !empty($det['exam_date']) ? $det['exam_date'] : Carbon::parse($det['start_time'])->format('Y-m-d'),
                            'start_time' => $det['start_time'],
                            'end_time' => $det['end_time'],
                            'type' => $isMat ? 'material' : 'exam',
                            'module_id' => !$isMat ? ($det['module_id'] ?: null) : null,
                            'exam_type' => $det['exam_type'] ?? 'resmi',
                            'allow_repeat' => (bool)($det['allow_repeat'] ?? false),
                            'require_token' => (bool)($det['require_token'] ?? false),
                            'token' => $token,
                            'is_camera' => (bool)($det['is_camera'] ?? false),
                            'is_recording' => (bool)($det['is_recording'] ?? false),
                            'is_streaming' => (bool)($det['is_streaming'] ?? false),
                            'digital_book_id' => $isMat ? ($det['digital_book_id'] ?: null) : null,
                            'require_attendance' => (bool)($det['require_attendance'] ?? false),
                            'order' => $order + 1,
                            'company_id' => Auth::user()->company_id,
                        ]
                    );
                    $existingDetailIds[] = $detailModel->id;

                    // Sync questions if module_id exists
                    if (($det['type'] ?? 'exam') === 'exam' && !empty($det['module_id'])) {
                        Timetable::syncModuleQuestions($timetable);
                    }
                }

                // Delete removed details
                TimetableDetail::where('timetable_id', $timetable->id)
                    ->whereNotIn('id', $existingDetailIds)
                    ->delete();

                DB::commit();
                AlertHelper::success('Berhasil', 'Jadwal dan Detail berhasil disimpan!');
                $this->closeModal();
                return;

            } catch (\Throwable $th) {
                DB::rollback();
                Log::error('Gagal Menyimpan Data Jadwal LEMES: ' . $th->getMessage());
                return AlertHelper::error('Gagal', 'Data gagal disimpan! ' . $th->getMessage());
            }
        }

        // ORIGINAL LOGIC WHEN IS_LEMES = FALSE
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
            'end_time.after' => 'Waktu Selesai harus lebih besar dari Waktu Mulai',
        ]);

        try {
            DB::beginTransaction();

            $existingData = $this->data_id ? Timetable::find($this->data_id) : null;
            $code = $existingData?->code;
            if (!$code) {
                $codeAlphabet = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
                $token = '';
                $max = strlen($codeAlphabet);
                for ($i = 0; $i < 6; $i++) {
                    $token .= $codeAlphabet[random_int(0, $max - 1)];
                }
                $code = $token;
            }

            Timetable::updateOrCreate([
                'id' => $this->data_id,
            ], [
                'company_id' => Auth::user()->company_id,
                'classmate_id' => $this->classmate_id,
                'name' => $this->name,
                'module_id' => $this->module_id,
                'total_questions' => !empty($this->total_questions) ? (int)$this->total_questions : null,
                'exam_room_id' => $this->exam_room_id,
                'exam_session_id' => $this->exam_session_id,
                'supervisors' => is_array($this->supervisors) ? $this->supervisors : (json_decode($this->supervisors, true) ?: []),
                'start_time' => $this->start_time,
                'end_time' => $this->end_time,
                'description' => $this->description,
                'studys' => $this->studys ? json_encode(array_keys($this->studys)) : null,
                'require_seb' => $this->require_seb ?? false,
                'is_camera' => $this->is_camera ?? false,
                'is_recording' => $this->is_recording ?? false,
                'is_streaming' => $this->is_streaming ?? false,
                'is_simulation' => $this->is_simulation ?? 'false',
                'allow_repeat' => (bool)$this->allow_repeat,
                'require_token' => (bool)$this->require_token,
                'code' => $code,
            ]);

            DB::commit();
            AlertHelper::success('Berhasil', 'Data berhasil disimpan!');
            $this->closeModal();

        } catch (\Throwable $th) {
            DB::rollback();
            AlertHelper::error('Gagal', 'Data gagal disimpan!'.$th->getMessage());
            return Log::error('Gagal Menyimpan Data Jadwal : '.$th);
        }
    }

    public function extraTimeModal($id)
    {
        $this->data_id = $id;
        $data = Timetable::find($id);
        $this->extra_time = $data->end_time;

        return $this->dispatch('open-modal', ['id' => 'modal-timetable-extra-time']);
    }

    public function closeModalExtraTime()
    {
        return $this->dispatch('close-modal', ['id' => 'modal-timetable-extra-time']);
    }

    public function submitExtraTime()
    {
        $this->validate([
            'extra_time' => 'required',
        ], [
            'extra_time.required' => 'Waktu Tambahan wajib diisi',
        ]);

        try {
            DB::beginTransaction();
            $data = Timetable::find($this->data_id);
            $data->update([
                'end_time' => $this->extra_time,
                'extra_time' => $this->extra_time,
            ]);
            DB::commit();
            $this->reset(['extra_time', 'data_id']);
            AlertHelper::success('Berhasil', 'Data berhasil ditambahkan!');
            $this->closeModalExtraTime();
        } catch (\Throwable $th) {
            DB::rollback();
            AlertHelper::error('Gagal', 'Data gagal ditambahkan!');

            return Log::info('Gagal Menambahkan Data Jadwal : '.$th);
        }
    }

    public function toggleRecording($id)
    {
        try {
            $timetable = Timetable::findOrFail($id);
            $timetable->update([
                'is_recording' => ! $timetable->is_recording,
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
                'is_streaming' => ! $timetable->is_streaming,
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
        $withRelations = ['module', 'examRoom', 'examSession'];
        if (is_lemes()) {
            $withRelations = array_merge($withRelations, [
                'classmate.classmateStudents.user',
                'timetableDetails.module',
                'timetableDetails.examRoom',
                'timetableDetails.examSession',
                'timetableDetails.digitalBook',
                'timetableDetails.attendances',
                'attendances',
            ]);
        }

        $timetable = Timetable::query()
            ->with($withRelations)
            ->when(! is_lemes(), function ($query) {
                // Ketika is_lemes = false, bagian materi tidak perlu muncul (hanya ujian CBT)
                $query->where(function ($q) {
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
            })
            ->when(auth()->user()->hasRole(['Pengawas', 'pengawas']), function ($query) {
                $userId = (string) auth()->id();
                $query->where(function ($q) use ($userId) {
                    $q->whereJsonContains('supervisors', $userId)
                      ->orWhereJsonContains('supervisors', (int) $userId)
                      ->orWhereRaw("supervisors::text LIKE ?", ['%"' . $userId . '"%']);
                });
            })
            ->when($this->search, function ($query, $search) {
                $query->where('name', 'ilike', '%'.$search.'%')
                    ->orWhere('start_time', 'ilike', '%'.$search.'%')
                    ->orWhere('end_time', 'ilike', '%'.$search.'%')
                    ->orWhere('description', 'ilike', '%'.$search.'%');
            })
            ->when($this->filter_simulation !== 'all', function ($query) {
                $query->where('is_simulation', $this->filter_simulation);
            })
            ->orderBy('order', 'desc')
            ->paginate($this->perPage);

        $companyId = Auth::user()?->company_id;
        $examRooms = ExamRoom::where('company_id', $companyId)->get();
        $examSessions = ExamSession::where('company_id', $companyId)->get();

        return view('livewire.admin.master.timetable.admin-master-timetable-index', [
            'timetables' => $timetable,
            'examRooms' => $examRooms,
            'examSessions' => $examSessions,
            'availableSupervisors' => $this->availableSupervisors ?? [],
        ])
            ->extends('layout.app')
            ->section('content');
    }

    // ==========================================
    // LEMES ATTENDANCE SCANNER & RECAP METHODS
    // ==========================================

    public function openAttendanceScanner($timetableId = null, $detailId = null)
    {
        if (!$timetableId) {
            $first = Timetable::where('company_id', Auth::user()->company_id)->latest()->first();
            $timetableId = $first?->id;
        }

        $this->scan_timetable_id = $timetableId;
        $this->scan_detail_id = $detailId;
        $this->recentScanned = [];
        $this->selected_scanner_timetable = $timetableId ? Timetable::with(['classmate.classmateStudents.user', 'timetableDetails'])->find($timetableId) : null;
        $this->openAttendanceModal = true;
        return $this->dispatch('open-modal', ['id' => 'modal-camera-attendance']);
    }

    public function closeAttendanceScanner()
    {
        $this->openAttendanceModal = false;
        $this->reset(['scan_timetable_id', 'scan_detail_id', 'selected_scanner_timetable']);
        return $this->dispatch('close-modal', ['id' => 'modal-camera-attendance']);
    }

    public function processAttendanceScan($qrPayload, $detailId = null)
    {
        try {
            $timetableId = $this->scan_timetable_id;
            $activeDetailId = $detailId ?: $this->scan_detail_id;

            if (!$timetableId) {
                $this->dispatch('scan-error', ['message' => 'Jadwal belum dipilih!']);
                return;
            }

            $timetable = Timetable::with(['classmate.classmateStudents.user'])->find($timetableId);
            if (!$timetable) {
                $this->dispatch('scan-error', ['message' => 'Jadwal tidak ditemukan!']);
                return;
            }

            // Decode payload
            $userId = null;
            $username = null;
            $decoded = json_decode($qrPayload, true);
            if (is_array($decoded)) {
                $userId = $decoded['user_id'] ?? null;
                $username = $decoded['username'] ?? null;
            } else {
                $user = User::where('id', $qrPayload)->orWhere('username', $qrPayload)->first();
                if ($user) {
                    $userId = $user->id;
                    $username = $user->username;
                }
            }

            if (!$userId && $username) {
                $user = User::where('username', $username)->first();
                $userId = $user?->id;
            }

            if (!$userId) {
                $this->dispatch('scan-error', ['message' => 'Format QRCODE tidak dikenali: ' . substr($qrPayload, 0, 30)]);
                return;
            }

            $student = User::with('userDetail')->find($userId);
            if (!$student) {
                $this->dispatch('scan-error', ['message' => 'Data peserta tidak ditemukan di sistem!']);
                return;
            }

            // Verify student is in classmate
            $isClassmate = false;
            if ($timetable->classmate) {
                $isClassmate = $timetable->classmate->classmateStudents()->where('user_id', $student->id)->exists();
            }
            if (!$isClassmate) {
                $this->dispatch('scan-error', [
                    'message' => 'Peserta ' . $student->name . ' (' . $student->username . ') tidak terdaftar di kelas jadwal ini!'
                ]);
                return;
            }

            // Record attendance
            DB::beginTransaction();
            TimetableAttendance::updateOrCreate(
                [
                    'timetable_id' => $timetableId,
                    'timetable_detail_id' => $activeDetailId ?: null,
                    'user_id' => $student->id,
                ],
                [
                    'scanned_by' => Auth::id(),
                    'attended_at' => now(),
                    'status' => 'present',
                    'method' => 'camera_scan',
                    'company_id' => Auth::user()->company_id,
                ]
            );

            // Also update or touch UserTimetable
            UserTimetable::firstOrCreate(
                [
                    'user_id' => $student->id,
                    'timetable_id' => $timetableId,
                ],
                [
                    'start_process' => now(),
                    'studys' => $timetable->studys,
                    'is_camera' => $timetable->is_camera ?? false,
                    'is_recording' => $timetable->is_recording ?? false,
                    'is_streaming' => $timetable->is_streaming ?? false,
                ]
            );

            DB::commit();

            $scanInfo = [
                'name' => $student->name,
                'username' => $student->username,
                'time' => now()->format('H:i:s'),
                'status' => 'Hadir',
            ];

            array_unshift($this->recentScanned, $scanInfo);
            if (count($this->recentScanned) > 10) {
                array_pop($this->recentScanned);
            }

            $this->dispatch('scan-success', [
                'student' => $scanInfo,
                'message' => 'Absensi berhasil untuk ' . $student->name
            ]);

        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error('processAttendanceScan error: ' . $th->getMessage());
            $this->dispatch('scan-error', ['message' => 'Terjadi kesalahan sistem: ' . $th->getMessage()]);
        }
    }

    public function openAttendanceRecap($timetableId, $detailId = null)
    {
        $this->scan_timetable_id = $timetableId;
        $this->scan_detail_id = $detailId;
        $timetable = Timetable::with(['classmate.classmateStudents.user.userDetail', 'attendances'])->find($timetableId);
        if (!$timetable) {
            return AlertHelper::error('Gagal', 'Jadwal tidak ditemukan.');
        }

        $attendedUserIds = $timetable->attendances
            ->when($detailId, fn($q) => $q->where('timetable_detail_id', $detailId))
            ->pluck('user_id')
            ->toArray();

        $students = [];
        if ($timetable->classmate) {
            foreach ($timetable->classmate->classmateStudents as $cs) {
                if ($cs->user) {
                    $hasAttended = in_array($cs->user->id, $attendedUserIds);
                    $attRecord = $hasAttended ? $timetable->attendances->firstWhere('user_id', $cs->user->id) : null;
                    $students[] = [
                        'id' => $cs->user->id,
                        'name' => $cs->user->name,
                        'username' => $cs->user->username,
                        'has_attended' => $hasAttended,
                        'attended_at' => $attRecord?->attended_at ? Carbon::parse($attRecord->attended_at)->format('d/m/Y H:i') : '-',
                    ];
                }
            }
        }

        $this->attendanceSummary = $students;
        $this->openAttendanceRecapModal = true;
        return $this->dispatch('open-modal', ['id' => 'modal-recap-attendance']);
    }

    public function closeAttendanceRecap()
    {
        $this->openAttendanceRecapModal = false;
        $this->reset(['attendanceSummary']);
        return $this->dispatch('close-modal', ['id' => 'modal-recap-attendance']);
    }

    public function markManualAttendance($userId, $status = 'present')
    {
        try {
            $timetableId = $this->scan_timetable_id;
            $detailId = $this->scan_detail_id;

            if ($status === 'present') {
                TimetableAttendance::updateOrCreate(
                    [
                        'timetable_id' => $timetableId,
                        'timetable_detail_id' => $detailId ?: null,
                        'user_id' => $userId,
                    ],
                    [
                        'scanned_by' => Auth::id(),
                        'attended_at' => now(),
                        'status' => 'present',
                        'method' => 'manual',
                        'company_id' => Auth::user()->company_id,
                    ]
                );
                AlertHelper::success('Berhasil', 'Peserta ditandai hadir.');
            } else {
                TimetableAttendance::where('timetable_id', $timetableId)
                    ->when($detailId, fn($q) => $q->where('timetable_detail_id', $detailId))
                    ->where('user_id', $userId)
                    ->delete();
                AlertHelper::success('Berhasil', 'Status kehadiran dihapus.');
            }

            $this->openAttendanceRecap($timetableId, $detailId);
        } catch (\Throwable $th) {
            AlertHelper::error('Gagal', 'Gagal mengubah status: ' . $th->getMessage());
        }
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
            if (! $timetableId) {
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
                'module',
                'timetableModule',
                'examRoom',
                'examSession',
                'classmate' => function ($q) {
                    $q->with(['classmateStudents.user.userDetail']);
                },
            ])->findOrFail($id);

            $company = Auth::user()->company()->with('companyDetail')->first();

            $pdf = Pdf::loadView('livewire.admin.master.timetable.admin-master-timetable-card-pdf', [
                'timetable' => $timetable,
                'company' => $company,
            ])->setPaper('a4', 'portrait');

            return response()->streamDownload(
                fn () => print ($pdf->output()),
                'kartu-peserta-'.\Str::slug($timetable->name).'.pdf'
            );

        } catch (\Throwable $th) {
            AlertHelper::error('Gagal', 'Gagal mencetak kartu peserta.');
            Log::error('Gagal cetak kartu peserta', [
                'error' => $th->getMessage(),
                'trace' => $th->getTraceAsString(),
            ]);
        }
    }

    public function openDetailQrModal($detailId)
    {
        $detail = TimetableDetail::with(['timetable.classmate', 'examRoom', 'examSession', 'module', 'digitalBook'])->find($detailId);
        if (!$detail) {
            return AlertHelper::error('Gagal', 'Detail jadwal tidak ditemukan.');
        }

        $this->selected_qr_detail = $detail;
        $this->selected_qr_timetable = $detail->timetable;
        $this->selected_qr_base64 = (new DNS2D())->getBarcodePNG($detail->code, 'QRCODE', 10, 10);

        return $this->dispatch('open-modal', ['id' => 'modal-detail-qrcode']);
    }

    public function closeDetailQrModal()
    {
        $this->reset(['selected_qr_detail', 'selected_qr_base64', 'selected_qr_timetable']);
        return $this->dispatch('close-modal', ['id' => 'modal-detail-qrcode']);
    }

    public function printDetailQrPdf($detailId)
    {
        try {
            $detail = TimetableDetail::with(['timetable.classmate', 'examRoom', 'examSession', 'module', 'digitalBook'])->findOrFail($detailId);
            $company = Auth::user()->company()->with('companyDetail')->first();
            $base64Qr = (new DNS2D())->getBarcodePNG($detail->code, 'QRCODE', 12, 12);

            $pdf = Pdf::loadView('livewire.admin.master.timetable.admin-master-timetable-session-qrcode-pdf', [
                'detail' => $detail,
                'timetable' => $detail->timetable,
                'company' => $company,
                'base64Qr' => $base64Qr,
            ])->setPaper('a4', 'portrait');

            return response()->streamDownload(
                fn () => print ($pdf->output()),
                'qrcode-presensi-' . Str::slug($detail->code) . '.pdf'
            );
        } catch (\Throwable $th) {
            AlertHelper::error('Gagal', 'Gagal mencetak lembar QRCODE: ' . $th->getMessage());
            Log::error('Gagal cetak lembar QRCODE', [
                'error' => $th->getMessage(),
                'trace' => $th->getTraceAsString(),
            ]);
        }
    }

    public function syncQuestions($id)
    {
        try {
            DB::beginTransaction();
            $timetable = Timetable::find($id);
            if (!$timetable) {
                return AlertHelper::error('Gagal', 'Jadwal tidak ditemukan.');
            }

            Timetable::syncModuleQuestions($timetable);
            DB::commit();
            AlertHelper::success('Berhasil', 'Jadwal "' . $timetable->name . '" berhasil disinkronkan.');
        } catch (\Throwable $th) {
            DB::rollback();
            Log::error('Gagal Sinkronisasi Jadwal: ' . $th);
            AlertHelper::error('Gagal', 'Gagal menyinkronkan jadwal!');
        }
    }

    public function syncAllQuestions()
    {
        try {
            DB::beginTransaction();
            $timetables = Timetable::where('company_id', Auth::user()->company_id)->get();
            if ($timetables->isEmpty()) {
                return AlertHelper::info('Info', 'Tidak ada jadwal ujian untuk disinkronkan.');
            }

            foreach ($timetables as $timetable) {
                Timetable::syncModuleQuestions($timetable);
            }

            DB::commit();
            AlertHelper::success('Berhasil', 'Semua jadwal ujian berhasil disinkronkan.');
        } catch (\Throwable $th) {
            DB::rollback();
            Log::error('Gagal Sinkronisasi Semua Jadwal: ' . $th);
            AlertHelper::error('Gagal', 'Gagal menyinkronkan semua jadwal!');
        }
    }

    public function startExamShortcut($timetableId)
    {
        if (Auth::user()->hasRole(['Pengawas', 'pengawas'])) {
            return AlertHelper::error('Akses Ditolak', 'Fitur ujicoba ini hanya untuk Admin.');
        }

        try {
            $timetable = Timetable::findOrFail($timetableId);

            $userTimetable = UserTimetable::where('user_id', Auth::id())
                ->where('timetable_id', $timetable->id)
                ->first();

            if (! $userTimetable) {
                $userTimetable = UserTimetable::create([
                    'user_id' => Auth::id(),
                    'timetable_id' => $timetable->id,
                    'start_process' => now(),
                    'start_exam' => now(),
                    'status' => 'exam',
                    'studys' => $timetable->studys,
                    'is_camera' => $timetable->is_camera ?? false,
                    'is_recording' => $timetable->is_recording ?? false,
                    'is_streaming' => $timetable->is_streaming ?? false,
                ]);
            } else {
                $userTimetable->update([
                    'status' => 'exam',
                    'start_exam' => $userTimetable->start_exam ?? now(),
                ]);
            }

            // Populate questions if not already created
            $questionCount = \App\Models\User\UserModuleQuestion::withoutGlobalScopes()
                ->where('user_timetable_id', $userTimetable->id)
                ->count();
            if ($questionCount === 0 && $timetable->timetableModule) {
                $timetableQuestions = \App\Models\Timetable\TimetableQuestion::withoutGlobalScope('user_scope')
                    ->where('timetable_module_id', $timetable->timetableModule->id)
                    ->orderBy('order')
                    ->get();

                $now = now();
                $companyId = Auth::user()->company_id;
                $userModuleQuestionsData = [];

                foreach ($timetableQuestions as $index => $tq) {
                    $userModuleQuestionsData[] = [
                        'id' => (string) \Illuminate\Support\Str::uuid(),
                        'user_timetable_id' => $userTimetable->id,
                        'timetable_module_id' => $timetable->timetableModule->id,
                        'timetable_question_id' => $tq->id,
                        'study_id' => $tq->study_id,
                        'company_id' => $companyId,
                        'order' => $index + 1,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ];
                }

                if (! empty($userModuleQuestionsData)) {
                    $chunks = array_chunk($userModuleQuestionsData, 200);
                    foreach ($chunks as $chunk) {
                        \App\Models\User\UserModuleQuestion::insert($chunk);
                    }
                }
            }

            return redirect()->route('admin.exam.detail.react', [
                'userTimetableId' => $userTimetable->id,
            ]);
        } catch (\Throwable $e) {
            Log::error('startExamShortcut error: ' . $e->getMessage());
            return AlertHelper::error('Gagal', 'Terjadi kesalahan saat membuka halaman ujian: ' . $e->getMessage());
        }
    }

    public function openModalSupervisor($id)
    {
        $this->timetable_id_supervisor = $id;
        $timetable = Timetable::find($id);

        if (! $timetable) {
            return AlertHelper::error('Gagal', 'Jadwal tidak ditemukan.');
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

        return $this->dispatch('open-modal', ['id' => 'modal-change-supervisor-master']);
    }

    public function closeModalSupervisor()
    {
        $this->reset(['timetable_id_supervisor', 'selectedSupervisors']);
        return $this->dispatch('close-modal', ['id' => 'modal-change-supervisor-master']);
    }

    public function saveSupervisor()
    {
        try {
            $timetable = Timetable::find($this->timetable_id_supervisor);
            if (! $timetable) {
                return AlertHelper::error('Gagal', 'Jadwal tidak ditemukan.');
            }

            $timetable->update([
                'supervisors' => array_values(array_filter($this->selectedSupervisors))
            ]);

            AlertHelper::success('Berhasil', 'Pengawas ujian berhasil diperbarui.');
            return $this->closeModalSupervisor();
        } catch (\Throwable $th) {
            Log::error('saveSupervisor error: ' . $th->getMessage());
            return AlertHelper::error('Gagal', 'Gagal memperbarui pengawas: ' . $th->getMessage());
        }
    }
}
