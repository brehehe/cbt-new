<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Illuminate\Support\Facades\Auth;

class LecturerExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return User::companyRole('Dosen', Auth::user()->company_id)
            ->where('type_user', 'employee')
            ->with(['userDetail', 'companyRoles' => function ($query) {
                $query->where('company_id', Auth::user()->company_id);
            }])
            ->orderBy('name', 'asc')
            ->get();
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'Name',
            'Username',
            'Email',
            'Phone',
            'NIDN/NIP',
            'Faculty',
            'Department',
            'Position',
        ];
    }

    /**
     * @param mixed $lecturer
     * @return array
     */
    public function map($lecturer): array
    {
        $detail = $lecturer->userDetail;

        return [
            $lecturer->name,
            $lecturer->username,
            $lecturer->email,
            $lecturer->phone,
            $detail ? ($detail->lecturer_nidn ?? '-') : '-',
            $detail ? ($detail->lecturer_faculty ?? '-') : '-',
            $detail ? ($detail->lecturer_department ?? '-') : '-',
            $detail ? ($detail->lecturer_position ?? '-') : '-',
        ];
    }
}
