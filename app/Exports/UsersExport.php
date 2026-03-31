<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class UsersExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return User::all();
    }

    public function headings(): array
    {
        return [
            'ID', 'Username', 'Email', 'Full Name', 'Full Name with Initials', 'NIC Number',
            'Birthday', 'Marital Status', 'Address', 'Permanent Address', 'Phone Number',
            'Mobile No', 'WhatsApp No', 'Designation', 'Grade', 'Section', 'Workplace',
            'WOP No', 'Appointment Date', 'Assumed Duty Date', 'Service History',
            'Current Office Details', 'Confirmation Details', 'EB Exams',
            'Educational Qualifications', 'Professional Qualifications', 'Trainings', 'Role'
        ];
    }

    public function map($user): array
    {
        return [
            $user->id,
            $user->name,
            $user->email,
            $user->full_name,
            $user->full_name_with_initials,
            $user->nic_number,
            $user->birthday ? \Carbon\Carbon::parse($user->birthday)->format('Y-m-d') : null,
            $user->marital_status,
            $user->address,
            $user->permanent_address,
            $user->phone_number,
            $user->mobile_no,
            $user->whatsapp_no,
            $user->designation,
            $user->grade,
            $user->section,
            $user->workplace,
            $user->wop_no,
            $user->appointment_date ? \Carbon\Carbon::parse($user->appointment_date)->format('Y-m-d') : null,
            $user->assumed_duty_date ? \Carbon\Carbon::parse($user->assumed_duty_date)->format('Y-m-d') : null,
            $user->service_history,
            $user->current_office_details,
            $user->confirmation_details,
            $user->eb_exams,
            $user->edu_qualifications,
            $user->prof_qualifications,
            $user->trainings,
            $user->role,
        ];
    }
}
