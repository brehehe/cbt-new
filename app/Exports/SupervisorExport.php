<?php

namespace App\Exports;

use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class SupervisorExport implements FromCollection, ShouldAutoSize, WithHeadings, WithMapping
{
    /**
     * @return Collection
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
     * @param  mixed  $supervisor
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
