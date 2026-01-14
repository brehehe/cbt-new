<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Illuminate\Support\Facades\Auth;

class SupervisorExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return User::companyRole('Pengawas', Auth::user()->company_id)
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
            'Position',
        ];
    }

    /**
     * @param mixed $supervisor
     * @return array
     */
    public function map($supervisor): array
    {
        $detail = $supervisor->userDetail;

        return [
            $supervisor->name,
            $supervisor->username,
            $supervisor->email,
            $supervisor->phone,
            $detail ? ($detail->supervisor_position ?? '-') : '-',
        ];
    }
}
