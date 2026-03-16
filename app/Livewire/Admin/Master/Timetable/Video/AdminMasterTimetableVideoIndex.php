<?php

namespace App\Livewire\Admin\Master\Timetable\Video;

use App\Models\Exam\ExamRecording;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Master\Timetable\Timetable;
use App\Models\Master\Question\Module;
use App\Models\User;
use Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class AdminMasterTimetableVideoIndex extends Component
{
    use WithPagination;
    public $timetable_id, $timetable, $modules = [], $supervisors = [], $module_id, $getSupervisors = [];
    public $search = '', $perPage = 5, $start_time, $end_time;

    public function mount($timetable_id = null)
    {
        $this->timetable_id = $timetable_id;

        if (!$this->timetable_id) {
            return redirect()->route('admin.master.timetable');
        }

        $timetable = Timetable::with('userTimetables')->find($this->timetable_id);
        if (!$timetable) {
            return redirect()->route('admin.master.timetable');
        }

        $this->modules = Module::select('id', 'name')->get()->pluck('name', 'id')->toArray();
        $this->getSupervisors = User::companyRole('Pengawas', Auth::user()->company_id)->select('name', 'id')->get()->pluck('name', 'id')->toArray();
        $this->timetable = $timetable->toArray();
        $this->supervisors = json_decode($timetable['supervisors']) ?? [];
        $this->module_id = $timetable['module_id'];
        $this->start_time = Carbon::parse($timetable->start_time)->format('d/m/Y H:i');
        $this->end_time = Carbon::parse($timetable->end_time)->format('d/m/Y H:i');
    }

    public function downloadZip()
    {
        $recordings = ExamRecording::where('timetable_id', $this->timetable_id)
            ->whereNotNull('video_path')
            ->get();

        if ($recordings->isEmpty()) {
            return $this->dispatch('swal:alert', [
                'type' => 'error',
                'title' => 'Gagal',
                'text' => 'Tidak ada file video untuk didownload.',
            ]);
        }

        $zipName = 'Exam_Recordings_' . str_replace(' ', '_', $this->timetable['name']) . '_' . date('Ymd_His') . '.zip';
        $zipPath = storage_path('app/public/temp/' . $zipName);

        // Ensure temp directory exists
        if (!file_exists(storage_path('app/public/temp'))) {
            mkdir(storage_path('app/public/temp'), 0755, true);
        }

        $zip = new \ZipArchive();
        if ($zip->open($zipPath, \ZipArchive::CREATE) === TRUE) {
            foreach ($recordings as $recording) {
                $filePath = storage_path('app/public/' . $recording->video_path);
                if (file_exists($filePath)) {
                    $zip->addFile($filePath, 'exam-recording/' . basename($recording->video_path));
                }
            }
            $zip->close();
        } else {
            return $this->dispatch('swal:alert', [
                'type' => 'error',
                'title' => 'Gagal',
                'text' => 'Gagal membuat file ZIP.',
            ]);
        }

        return response()->download($zipPath)->deleteFileAfterSend(true);
    }

    public function confirmDeleteAll()
    {
        $this->dispatch('swal:confirm', [
            'title' => 'Hapus Semua Video?',
            'text' => 'Semua file video dan rekaman untuk ujian ini akan dihapus permanen!',
            'type' => 'warning',
            'method' => 'deleteAllRecordings',
        ]);
    }

    public function deleteAllRecordings()
    {
        try {
            \DB::beginTransaction();
            $recordings = ExamRecording::where('timetable_id', $this->timetable_id)->get();
            
            foreach ($recordings as $recording) {
                if ($recording->video_path) {
                    $filePath = storage_path('app/public/' . $recording->video_path);
                    if (file_exists($filePath)) {
                        unlink($filePath);
                    }
                }
                $recording->delete(); // Soft delete because of model trait
            }

            \DB::commit();
            $this->dispatch('swal:alert', [
                'type' => 'success',
                'title' => 'Berhasil',
                'text' => 'Semua video dan rekaman berhasil dihapus.',
            ]);
        } catch (\Throwable $th) {
            \DB::rollback();
            Log::error('Gagal menghapus semua rekaman: ' . $th->getMessage());
            $this->dispatch('swal:alert', [
                'type' => 'error',
                'title' => 'Gagal',
                'text' => 'Terjadi kesalahan saat menghapus data.',
            ]);
        }
    }

    public function render()
    {
        $examRecordings = ExamRecording::where('timetable_id', $this->timetable_id)
            ->search($this->search)
            ->whereNotNull('video_path')
            ->with(['userTimetable', 'userTimetable.user'])
            ->paginate($this->perPage);

        return view('livewire.admin.master.timetable.video.admin-master-timetable-video-index', [
            'examRecordings' => $examRecordings,
        ])
            ->extends('layout.app')
            ->section('content');
    }
}
