<?php

namespace App\Exports;

use App\Models\Master\Timetable\Timetable;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class TimetableAttendanceExport implements FromCollection, ShouldAutoSize, WithHeadings, WithMapping
{
    protected $timetable_id;
    protected $detail_id;
    protected $search;

    public function __construct($timetable_id, $detail_id = null, $search = '')
    {
        $this->timetable_id = $timetable_id;
        $this->detail_id = $detail_id;
        $this->search = $search;
    }

    public function collection()
    {
        $timetable = Timetable::with(['classmate.classmateStudents.user.userDetail', 'attendances'])->find($this->timetable_id);
        if (!$timetable || !$timetable->classmate) {
            return collect();
        }

        $attendedRecords = $timetable->attendances
            ->when($this->detail_id, fn($q) => $q->where('timetable_detail_id', $this->detail_id))
            ->keyBy('user_id');

        $students = collect();
        foreach ($timetable->classmate->classmateStudents as $cs) {
            if ($cs->user) {
                $user = $cs->user;
                if ($this->search) {
                    $s = strtolower($this->search);
                    $matches = str_contains(strtolower($user->name), $s) ||
                               str_contains(strtolower($user->username ?? ''), $s) ||
                               str_contains(strtolower($user->nim ?? ''), $s);
                    if (!$matches) {
                        continue;
                    }
                }

                $hasAttended = isset($attendedRecords[$user->id]);
                $att = $attendedRecords[$user->id] ?? null;

                $students->push([
                    'user' => $user,
                    'has_attended' => $hasAttended,
                    'attended_at' => $att?->attended_at ? Carbon::parse($att->attended_at)->format('d/m/Y H:i:s') : '-',
                    'method' => $att?->method ? ucfirst($att->method) : '-',
                ]);
            }
        }

        return $students;
    }

    public function headings(): array
    {
        $idLabel = function_exists('student_label') && student_label() === 'Kenshi' ? 'Nomer Kenshi / Username' : 'NIM / Username';

        return [
            'No',
            $idLabel,
            'Nama Lengkap',
            'Status Kehadiran',
            'Waktu Presensi',
            'Metode Presensi',
        ];
    }

    public function map($row): array
    {
        static $no = 0;
        $no++;

        return [
            $no,
            $row['user']->nim ?? ($row['user']->username ?? '-'),
            $row['user']->name ?? '-',
            $row['has_attended'] ? 'HADIR' : 'BELUM HADIR',
            $row['attended_at'],
            $row['method'],
        ];
    }
}
