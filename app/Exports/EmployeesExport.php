<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class EmployeesExport implements FromCollection, WithHeadings, WithMapping
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return User::where('role', '!=', 'admin')->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Full Name',
            'Email',
            'Designation',
            'Workplace',
            'Salary (Rs)',
            'Registered At',
        ];
    }

    public function map($employee): array
    {
        return [
            $employee->id,
            $employee->full_name ?? $employee->name,
            $employee->email,
            $employee->designation,
            $employee->workplace,
            $employee->salary,
            $employee->created_at->format('Y-m-d H:i:s'),
        ];
    }
}

