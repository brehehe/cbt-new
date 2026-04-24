<?php

namespace App\Exports;

use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class StudentExport implements FromCollection, ShouldAutoSize, WithHeadings, WithMapping
{
    /**
     * @return Collection
     */
    public function collection()
    {
        return User::companyRole('Mahasiswa', Auth::user()->company_id)
            ->where('type_user', 'employee')
            ->with(['userDetail', 'study', 'companyRoles' => function ($query) {
                $query->where('company_id', Auth::user()->company_id);
            }])
            ->orderBy('name', 'asc')
            ->get();
    }

    public function headings(): array
    {
        return [
            'Name',
            'NIM',
            'Username',
            'Email',
            'Phone',
            'Program Studi',
            'Type Study',
            'Faculty',
            'Department',
            'Semester',
            'Student Status',
        ];
    }

    /**
     * @param  mixed  $student
     */
    public function map($student): array
    {
        $detail = $student->userDetail;

        return [
            $student->name,
            $student->nim,
            $student->username ?? '-',
            $student->email,
            $student->phone,
            $student->study ? $student->study->name : '-',
            $student->type_study ?? '-',
            $detail ? ($detail->student_faculty ?? '-') : '-',
            $detail ? ($detail->student_department ?? '-') : '-',
            $detail ? ($detail->student_semester ?? '-') : '-',
            $detail ? ($detail->student_status ?? 'active') : 'active',
        ];
    }
}
