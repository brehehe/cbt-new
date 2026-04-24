<?php

namespace App\Exports;

use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class AdminExport implements FromCollection, ShouldAutoSize, WithHeadings, WithMapping
{
    /**
     * @return Collection
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
     * @param  mixed  $admin
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
