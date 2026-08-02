<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class StudentImportReportExport implements FromArray, ShouldAutoSize, WithHeadings, WithStyles, WithTitle
{
    protected array $results;

    public function __construct(array $results)
    {
        $this->results = $results;
    }

    public function array(): array
    {
        $rows = [];
        foreach ($this->results as $res) {
            $rows[] = [
                'row' => $res['row'] ?? '-',
                'name' => $res['name'] ?? '-',
                'nim' => $res['nim'] ?? '-',
                'email' => $res['email'] ?? '-',
                'status' => $res['status'] ?? '-',
                'reason' => $res['reason'] ?? '-',
            ];
        }

        return $rows;
    }

    public function headings(): array
    {
        return [
            'Baris Excel',
            'Nama Peserta',
            'NIM / Username',
            'Email',
            'Status Import',
            'Keterangan / Alasan Gagal',
        ];
    }

    public function title(): string
    {
        return 'Laporan Import Peserta';
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '1E293B'],
                ],
            ],
        ];
    }
}
