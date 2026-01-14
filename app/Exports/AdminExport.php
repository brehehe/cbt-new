<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Illuminate\Support\Facades\Auth;

class AdminExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return User::companyRole('Admin', Auth::user()->company_id)
            ->where('type_user', 'employee')
            ->with(['companyRoles' => function ($query) {
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
        ];
    }

    /**
     * @param mixed $admin
     * @return array
     */
    public function map($admin): array
    {
        return [
            $admin->name,
            $admin->username,
            $admin->email,
            $admin->phone,
        ];
    }
}
