<?php

namespace App\Exports;

use App\Models\Exam\ExamLiveSession;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ExamMonitorExport implements FromCollection, ShouldAutoSize, WithHeadings, WithMapping, WithStyles
{
    protected $selectedTimetable;

    protected $search;

    protected $statusFilter;

    protected $riskFilter;

    protected $sessionType;

    protected $utStatus;

    public function __construct(
        string $selectedTimetable = '',
        string $search = '',
        string $statusFilter = '',
        string $riskFilter = '',
        string $sessionType = 'all',
        string $utStatus = ''
    ) {
        $this->selectedTimetable = $selectedTimetable;
        $this->search = $search;
        $this->statusFilter = $statusFilter;
        $this->riskFilter = $riskFilter;
        $this->sessionType = $sessionType;
        $this->utStatus = $utStatus;
    }

    public function collection(): Collection
    {
        $query = ExamLiveSession::with(['user', 'timetable.module', 'userTimetable.userModuleQuestions'])
            ->orderBy('last_activity', 'desc');

        if ($this->sessionType === 'active') {
            $query->active();
        } elseif ($this->sessionType === 'history') {
            $query->where('is_active', false);
        }

        if ($this->selectedTimetable) {
            $query->where('timetable_id', $this->selectedTimetable);
        }

        if ($this->search) {
            $query->whereHas('user', function ($q) {
                $q->where('name', 'ilike', '%'.$this->search.'%')
                    ->orWhere('nim', 'ilike', '%'.$this->search.'%')
                    ->orWhere('username', 'ilike', '%'.$this->search.'%');
            });
        }

        if ($this->statusFilter) {
            $query->where('connection_status', $this->statusFilter);
        }

        if ($this->riskFilter) {
            switch ($this->riskFilter) {
                case 'high':
                    $query->where(function ($q) {
                        $q->where('alert_count', '>=', 5)->orWhere('warning_count', '>=', 10);
                    });
                    break;
                case 'medium':
                    $query->where(function ($q) {
                        $q->whereBetween('alert_count', [3, 4])
                            ->orWhereBetween('warning_count', [5, 9]);
                    });
                    break;
                case 'low':
                    $query->where(function ($q) {
                        $q->whereBetween('alert_count', [1, 2])
                            ->orWhereBetween('warning_count', [1, 4]);
                    });
                    break;
                case 'none':
                    $query->where('alert_count', 0)->where('warning_count', 0);
                    break;
            }
        }

        if ($this->utStatus) {
            $query->whereHas('userTimetable', function ($q) {
                $q->where('status', $this->utStatus);
            });
        }

        return $query->get();
    }

    public function headings(): array
    {
        return [
            'No',
            'Nama Mahasiswa',
            'NIM / Username',
            'Jadwal Ujian',
            'Modul',
            'Total Soal',
            'Terjawab',
            'Benar',
            'Salah',
            'Belum Dijawab',
            'Progress (%)',
            'Jumlah Alert',
            'Risk Level',
            'Status Koneksi',
            'Aktif',
            'Waktu Mulai',
            'IP Address',
            'Last Activity',
        ];
    }

    public function map($session): array
    {
        static $no = 0;
        $no++;

        $stats = $session->db_question_stats;

        $riskLabels = [
            'high' => 'Tinggi',
            'medium' => 'Sedang',
            'low' => 'Rendah',
            'none' => 'Aman',
        ];

        $statusLabels = [
            'connected' => 'Terhubung',
            'disconnected' => 'Terputus',
            'unstable' => 'Tidak Stabil',
        ];

        $startTime = isset($session->session_metadata['start_time'])
            ? \Carbon\Carbon::parse($session->session_metadata['start_time'])->format('d/m/Y H:i:s')
            : 'N/A';
        $ipAddress = $session->session_metadata['ip_address'] ?? 'N/A';

        return [
            $no,
            $session->user->name ?? 'Unknown',
            $session->user->nim ?? ($session->user->username ?? 'N/A'),
            $session->timetable->name ?? 'N/A',
            $session->timetable->module->name ?? 'N/A',
            $stats['total'],
            $stats['answered'],
            $stats['correct'],
            $stats['wrong'],
            $stats['unanswered'],
            $stats['percentage'].'%',
            $session->alert_count,
            $riskLabels[$session->risk_level] ?? $session->risk_level,
            $statusLabels[$session->connection_status] ?? $session->connection_status,
            $session->is_active ? 'Ya' : 'Tidak',
            $startTime,
            $ipAddress,
            $session->last_activity ? $session->last_activity->format('d/m/Y H:i:s') : 'N/A',
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
