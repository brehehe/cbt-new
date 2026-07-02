<?php

namespace App\Exports;

use App\Models\Classmate\ClassmateStudent;
use App\Models\Master\Timetable\Timetable;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class TimetableSessionExport implements FromCollection, ShouldAutoSize, WithHeadings, WithMapping, WithStyles
{
    protected $timetableId;
    protected $search;
    protected $classmateId;

    public function __construct(string $timetableId, string $search = '')
    {
        $this->timetableId = $timetableId;
        $this->search = $search;

        $timetable = Timetable::find($timetableId);
        $this->classmateId = $timetable?->classmate_id;
    }

    public function collection(): Collection
    {
        return ClassmateStudent::query()
            ->where('classmate_id', $this->classmateId)
            ->when($this->search, function ($q) {
                $q->whereHas('user', function ($uq) {
                    $uq->where('name', 'ilike', '%' . $this->search . '%')
                        ->orWhere('nim', 'ilike', '%' . $this->search . '%')
                        ->orWhere('username', 'ilike', '%' . $this->search . '%');
                });
            })
            ->with([
                'user.usrSecKey',
                'user.examLiveSessions' => function ($q) {
                    $q->where('timetable_id', $this->timetableId);
                },
                'user.userTimetables' => function ($q) {
                    $q->where('timetable_id', $this->timetableId)->with('userModuleQuestions');
                },
            ])
            ->get();
    }

    public function headings(): array
    {
        return [
            'No',
            'No Peserta (NIM)',
            'Password',
            'Nama Peserta',
            'Kehadiran',
            'Status Login / Aktif',
            'Jumlah Soal',
            'Terjawab',
            'Aktivitas Terakhir',
            'Kamera',
        ];
    }

    public function map($classmateStudent): array
    {
        static $no = 0;
        $no++;

        $user = $classmateStudent->user;
        $liveSession = $user?->examLiveSessions->first();
        $userTimetable = $user?->userTimetables->first();

        $kehadiranText = $userTimetable ? 'Hadir' : 'Tidak Hadir';

        $statusText = 'Belum Login';
        if ($liveSession) {
            if ($liveSession->is_active) {
                $statusText = 'Aktif (Online)';
            } else {
                $statusText = $liveSession->connection_status === 'disconnected' ? 'Offline' : 'Login';
            }
        }

        $totalSoal = $userTimetable ? $userTimetable->userModuleQuestions->count() : 0;
        $terjawab  = $userTimetable
            ? $userTimetable->userModuleQuestions->filter(fn($q) => $q->timetable_answer_id || $q->essay_answer)->count()
            : 0;

        return [
            $no,
            $user->nim ?? ($user->username ?? '-'),
            $user->decrypted_password ?? '-',
            $user->name ?? '-',
            $kehadiranText,
            $statusText,
            $totalSoal,
            $terjawab,
            $liveSession?->last_activity ?? '-',
            $liveSession?->camera_status ?? '-',
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
