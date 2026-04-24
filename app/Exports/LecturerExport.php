<?php

namespace App\Exports;

use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class LecturerExport implements FromCollection, ShouldAutoSize, WithHeadings, WithMapping
{
    /**
     * @return Collection
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
     * @param  mixed  $lecturer
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
