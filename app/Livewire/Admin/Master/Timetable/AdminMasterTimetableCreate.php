<?php

namespace App\Livewire\Admin\Master\Timetable;

use App\Helpers\AlertHelper;
use App\Models\Classmate\Classmate;
use App\Models\Classmate\ClassmateStudent;
use App\Models\Master\Exam\ExamRoom;
use App\Models\Master\Exam\ExamSession;
use App\Models\Master\Question\Module;
use App\Models\Master\Timetable\Timetable;
use App\Models\Study\Study;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Livewire\WithPagination;

class AdminMasterTimetableCreate extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    // Tabs & Multiple Schedules
    public $schedules = [];
    public $activeTab = 0;

    // Dropdowns
    public $modules = [];
    public $getSupervisors = [];
    public $examRooms = [];
    public $examSessions = [];

    // Participants (dynamic selection parameters)
    public $searchStudent = '';
    public $perPage = 10;
    public $openStudentModal = false;
    public $generateCount = 50;

    public function mount()
    {
        $this->modules = Module::whereNotNull('category_question_settings')
            ->whereRaw("category_question_settings <> '{}'::jsonb")
            ->pluck('name', 'id')
            ->toArray();
        $this->getSupervisors = User::companyRole('Pengawas', Auth::user()->company_id)
            ->select('name', 'id')->get()->pluck('name', 'id')->toArray();
        $this->examRooms = ExamRoom::where('company_id', Auth::user()->company_id)->get();
        $this->examSessions = ExamSession::where('company_id', Auth::user()->company_id)->get();

        $this->schedules = [
            $this->createNewScheduleState('Jadwal 1')
        ];
    }

    private function createNewScheduleState($name = '')
    {
        return [
            'name' => $name,
            'module_id' => '',
            'exam_room_id' => '',
            'exam_session_id' => '',
            'supervisors' => [],
            'start_time' => '',
            'end_time' => '',
            'description' => '',
            'require_seb' => false,
            'is_recording' => true,
            'is_streaming' => true,
            'selectedStudents' => [],
            'studys' => [],
        ];
    }

    public function updatedSchedules($value, $key)
    {
        // $key will look like "0.module_id" or "schedules.0.module_id"
        if (str_contains($key, 'module_id')) {
            preg_match('/(\d+)/', $key, $matches);
            if (isset($matches[0])) {
                $index = $matches[0];
                $this->updateStudiesForTab($index, $value);
            }
        }

        if (str_contains($key, 'start_time')) {
            preg_match('/(\d+)/', $key, $matches);
            if (isset($matches[0]) && $value) {
                $index = $matches[0];
                $startTime = Carbon::parse($value)->format('Y-m-d H:i:s');
                $this->schedules[$index]['start_time'] = $startTime;
                $this->schedules[$index]['end_time'] = Carbon::parse($startTime)->addHour(2)->format('Y-m-d H:i:s');
                
                try {
                    $this->validateOnly("schedules.{$index}.start_time");
                    $this->validateOnly("schedules.{$index}.end_time");
                } catch (\Illuminate\Validation\ValidationException $e) {
                    // Silent catch to let errors propagate to view
                }
            }
        }

        if (str_contains($key, 'end_time')) {
            preg_match('/(\d+)/', $key, $matches);
            if (isset($matches[0]) && $value) {
                $index = $matches[0];
                $this->schedules[$index]['end_time'] = Carbon::parse($value)->format('Y-m-d H:i:s');
                
                try {
                    $this->validateOnly("schedules.{$index}.end_time");
                    $this->validateOnly("schedules.{$index}.start_time");
                } catch (\Illuminate\Validation\ValidationException $e) {
                    // Silent catch to let errors propagate to view
                }
            }
        }
    }

    private function updateStudiesForTab($index, $moduleId)
    {
        if ($moduleId) {
            $module = Module::find($moduleId);
            if ($module && $module->studys) {
                $this->schedules[$index]['studys'] = Study::select('name', 'id')
                    ->whereIn('id', json_decode($module->studys))
                    ->get()->pluck('name', 'id')->toArray();
            } else {
                $this->schedules[$index]['studys'] = [];
            }
        } else {
            $this->schedules[$index]['studys'] = [];
        }
    }

    // Modal Student
    public function openModalStudent()
    {
        $this->openStudentModal = true;
        $this->reset('searchStudent');
        $this->dispatch('open-modal', ['id' => 'modalStudent']);
    }

    public function closeModalStudent()
    {
        $this->openStudentModal = false;
        $this->reset('searchStudent');
        $this->dispatch('close-modal', ['id' => 'modalStudent']);
    }

    public function updatedSearchStudent()
    {
        $this->resetPage('modalPage');
    }

    // Tab Management
    public function addTab()
    {
        $nextNum = count($this->schedules) + 1;
        $this->schedules[] = $this->createNewScheduleState('Jadwal ' . $nextNum);
        
        $newIndex = count($this->schedules) - 1;
        
        // Copy settings from the currently active tab to make bulk creation easier
        if ($this->activeTab >= 0 && $this->activeTab < count($this->schedules) - 1) {
            $prev = $this->schedules[$this->activeTab];
            $this->schedules[$newIndex]['module_id'] = $prev['module_id'];
            $this->schedules[$newIndex]['exam_session_id'] = $prev['exam_session_id'];
            $this->schedules[$newIndex]['supervisors'] = $prev['supervisors'];
            $this->schedules[$newIndex]['start_time'] = $prev['start_time'];
            $this->schedules[$newIndex]['end_time'] = $prev['end_time'];
            $this->schedules[$newIndex]['require_seb'] = $prev['require_seb'];
            $this->schedules[$newIndex]['is_recording'] = $prev['is_recording'];
            $this->schedules[$newIndex]['is_streaming'] = $prev['is_streaming'];
            $this->schedules[$newIndex]['studys'] = $prev['studys'];
        }

        $this->activeTab = $newIndex;
        $this->resetPage('modalPage');
    }

    public function removeTab($index)
    {
        if (count($this->schedules) <= 1) {
            return AlertHelper::error('Gagal', 'Minimal harus ada 1 jadwal.');
        }

        unset($this->schedules[$index]);
        $this->schedules = array_values($this->schedules);

        if ($this->activeTab >= count($this->schedules)) {
            $this->activeTab = count($this->schedules) - 1;
        }
        $this->resetPage('modalPage');
    }

    public function changeTab($index)
    {
        $this->activeTab = $index;
        $this->resetPage('modalPage');
    }

    public function getSelectedStudentsInOtherTabs($currentIndex)
    {
        $userIds = [];
        foreach ($this->schedules as $idx => $sched) {
            if ($idx != $currentIndex) {
                $userIds = array_merge($userIds, $sched['selectedStudents'] ?? []);
            }
        }
        return array_values(array_unique($userIds));
    }

    public function toggleStudent($user_id)
    {
        $user_id = (string) $user_id;
        $currentSelected = $this->schedules[$this->activeTab]['selectedStudents'] ?? [];
        if (in_array($user_id, $currentSelected)) {
            $currentSelected = array_values(array_diff($currentSelected, [$user_id]));
        } else {
            $otherSelected = $this->getSelectedStudentsInOtherTabs($this->activeTab);
            if (!in_array($user_id, $otherSelected)) {
                $currentSelected[] = $user_id;
            }
        }
        $this->schedules[$this->activeTab]['selectedStudents'] = $currentSelected;
    }

    public function removeStudent($user_id)
    {
        $user_id = (string) $user_id;
        $currentSelected = $this->schedules[$this->activeTab]['selectedStudents'] ?? [];
        $currentSelected = array_values(array_diff($currentSelected, [$user_id]));
        $this->schedules[$this->activeTab]['selectedStudents'] = $currentSelected;
    }

    public function rules()
    {
        return [
            'schedules' => 'required|array|min:1',
            'schedules.*.name' => 'required',
            'schedules.*.module_id' => 'required',
            'schedules.*.supervisors' => 'required|array|min:1',
            'schedules.*.start_time' => 'required|date',
            'schedules.*.end_time' => 'required|date|after:schedules.*.start_time',
            'schedules.*.exam_room_id' => 'required',
            'schedules.*.exam_session_id' => 'required',
            'generateCount' => 'required|numeric|min:1|max:1000',
        ];
    }

    public function messages()
    {
        return [
            'schedules.*.name.required' => 'Nama Jadwal wajib diisi',
            'schedules.*.module_id.required' => 'Modul wajib diisi',
            'schedules.*.exam_room_id.required' => 'Ruang ujian wajib diisi',
            'schedules.*.exam_session_id.required' => 'Sesi ujian wajib diisi',
            'schedules.*.supervisors.required' => 'Pengawas wajib diisi',
            'schedules.*.start_time.required' => 'Waktu Mulai wajib diisi',
            'schedules.*.end_time.required' => 'Waktu Selesai wajib diisi',
            'schedules.*.end_time.after' => 'Waktu Selesai harus lebih besar dari Waktu Mulai',
            'generateCount.required' => 'Jumlah generate peserta harus diisi',
            'generateCount.min' => 'Jumlah minimal adalah 1',
        ];
    }

    public function generateStudents()
    {
        $this->validateOnly('generateCount');

        $excludeIds = $this->getSelectedStudentsInOtherTabs($this->activeTab);
        $currentSelected = $this->schedules[$this->activeTab]['selectedStudents'] ?? [];
        $allExcludeIds = array_values(array_unique(array_merge($excludeIds, $currentSelected)));

        $company = Auth::user()->company;

        $query = User::role(['Mahasiswa'])
            ->where('company_id', $company->id);

        if (count($allExcludeIds) > 0) {
            $query->whereNotIn('id', $allExcludeIds);
        }

        $students = $query->limit($this->generateCount)
            ->pluck('id')
            ->map(fn($id) => (string) $id)
            ->toArray();

        $newSelected = array_values(array_unique(array_merge($currentSelected, $students)));
        $this->schedules[$this->activeTab]['selectedStudents'] = $newSelected;

        if (count($students) > 0) {
            AlertHelper::success('Berhasil', count($students) . ' siswa ditambahkan.');
        } else {
            AlertHelper::error('Info', 'Tidak ada siswa tersisa untuk di-generate.');
        }
    }

    public function getSelectedStudentsDataProperty()
    {
        $activeSched = $this->schedules[$this->activeTab] ?? null;
        if (!$activeSched || count($activeSched['selectedStudents']) === 0) return collect();
        return User::whereIn('id', $activeSched['selectedStudents'])->select('id', 'name')->get();
    }

    public function render()
    {
        $company = Auth::user()->company;

        $excludeIds = $this->getSelectedStudentsInOtherTabs($this->activeTab);

        $query = User::role(['Mahasiswa'])
            ->where('company_id', $company->id);

        if (count($excludeIds) > 0) {
            $query->whereNotIn('id', $excludeIds);
        }

        $query->search($this->searchStudent)
            ->orderBy('name', 'asc');

        $pageResults = $query->paginate($this->perPage, ['*'], 'modalPage');

        return view('livewire.admin.master.timetable.admin-master-timetable-create', [
            'studentsList' => $pageResults
        ])->extends('layout.app')->section('content');
    }

    public function submit()
    {
        $this->validate();

        // Validate that each schedule has at least one participant
        foreach ($this->schedules as $index => $sched) {
            if (empty($sched['selectedStudents'])) {
                return AlertHelper::error('Gagal', 'Jadwal "' . ($sched['name'] ?: 'Jadwal ' . ($index + 1)) . '" harus memiliki minimal 1 peserta.');
            }
        }

        try {
            DB::beginTransaction();

            foreach ($this->schedules as $index => $sched) {
                $scheduleName = $sched['name'];
                $uniqueGroupName = $scheduleName . ' - ' . date('YmdHis') . '-' . $index;

                // 1. Create Classmate group
                $classmate = Classmate::create([
                    'name' => $uniqueGroupName,
                    'company_id' => Auth::user()->company_id,
                    'description' => 'Grup peserta untuk jadwal: ' . $scheduleName,
                    'type_study' => 'general'
                ]);

                // 2. Attach Students to Classmate for this room
                foreach ($sched['selectedStudents'] as $userId) {
                    ClassmateStudent::create([
                        'classmate_id' => $classmate->id,
                        'user_id' => $userId,
                    ]);
                }

                // Get study keys from studys array
                $studyKeys = !empty($sched['studys']) ? array_keys($sched['studys']) : null;

                // 3. Create Timetable
                Timetable::create([
                    'company_id' => Auth::user()->company_id,
                    'classmate_id' => $classmate->id,
                    'name' => $scheduleName,
                    'module_id' => $sched['module_id'],
                    'exam_room_id' => $sched['exam_room_id'],
                    'exam_session_id' => $sched['exam_session_id'],
                    'supervisors' => json_encode($sched['supervisors']),
                    'start_time' => $sched['start_time'],
                    'end_time' => $sched['end_time'],
                    'description' => $sched['description'] ?? null,
                    'studys' => $studyKeys ? json_encode($studyKeys) : null,
                    'require_seb' => $sched['require_seb'] ?? false,
                    'is_recording' => $sched['is_recording'] ?? false,
                    'is_streaming' => $sched['is_streaming'] ?? false,
                ]);
            }

            DB::commit();
            
            $count = count($this->schedules);
            AlertHelper::success('Berhasil', $count > 1 ? $count . ' Jadwal dan Peserta berhasil disimpan!' : 'Jadwal dan Peserta berhasil disimpan!');
            
            return redirect()->route('admin.master.timetable');

        } catch (\Throwable $th) {
            DB::rollback();
            AlertHelper::error('Gagal', 'Data gagal disimpan! ' . $th->getMessage());
            Log::error('Gagal Menyimpan Data Jadwal Baru : ' . $th);
        }
    }
}
