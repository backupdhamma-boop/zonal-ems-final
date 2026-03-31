<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Service Record - {{ $employee->full_name ?? $employee->name }}</title>
    <style>
        @page { size: A4; margin: 1.5cm; }
        body { 
            font-family: 'Helvetica', 'Arial', sans-serif; 
            font-size: 10pt; 
            line-height: 1.6;
            color: #1e293b;
        }
        .header {
            text-align: center;
            margin-bottom: 25px;
            border-bottom: 2px solid #1e3a8a;
            padding-bottom: 15px;
        }
        .header h1 {
            color: #1e3a8a;
            margin: 0;
            font-size: 20pt;
            text-transform: uppercase;
        }
        .header p {
            margin: 5px 0;
            color: #64748b;
            font-size: 11pt;
        }
        .section-title {
            background-color: #f1f5f9;
            padding: 8px 12px;
            font-weight: bold;
            font-size: 12pt;
            border-left: 4px solid #1e3a8a;
            margin: 20px 0 10px 0;
            color: #1e3a8a;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }
        table tr td {
            padding: 8px 10px;
            border: 1px solid #e2e8f0;
            vertical-align: top;
        }
        .label {
            font-weight: bold;
            background-color: #f8fafc;
            width: 35%;
            color: #475569;
        }
        .photo-box {
            width: 120px;
            height: 150px;
            border: 1px solid #e2e8f0;
            text-align: center;
            position: absolute;
            top: 0;
            right: 0;
        }
        .photo-box img {
            max-width: 100%;
            max-height: 100%;
        }
        .footer {
            margin-top: 30px;
            font-size: 8pt;
            text-align: center;
            color: #94a3b8;
            border-top: 1px solid #e2e8f0;
            padding-top: 10px;
        }
        .sinhala {
            /* Fallback font stack for Sinhala */
            font-family: 'Iskoola Pota', 'Noto Sans Sinhala', 'Helvetica', sans-serif;
        }
        .page-break {
            page-break-after: always;
        }
    </style>
</head>
<body>

    <div class="header">
        <h1>Official Service Record</h1>
        <p>Zonal Education Office - Dehiovita</p>
    </div>

    <div style="position: relative; min-height: 160px;">
        <div class="photo-box">
            @if($employee->profile_photo_path)
                <img src="{{ public_path('storage/' . $employee->profile_photo_path) }}" alt="Profile">
            @else
                <div style="padding-top: 60px; color: #cbd5e1;">NO PHOTO</div>
            @endif
        </div>

        <div style="margin-right: 140px;">
            <div class="section-title" style="margin-top: 0;">1. Personal Information</div>
            <table>
                <tr>
                    <td class="label">Full Name</td>
                    <td class="sinhala">{{ $employee->full_name ?? $employee->name }}</td>
                </tr>
                <tr>
                    <td class="label">Name with Initials</td>
                    <td class="sinhala">{{ $employee->full_name_with_initials ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td class="label">NIC Number</td>
                    <td>{{ $employee->nic_number ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td class="label">Date of Birth</td>
                    <td>{{ $employee->birthday ? $employee->birthday->format('Y-m-d') : 'N/A' }}</td>
                </tr>
                <tr>
                    <td class="label">Marital Status</td>
                    <td>{{ $employee->marital_status ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td class="label">Race / Religion</td>
                    <td>{{ $employee->race_religion ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td class="label">Phone / Mobile</td>
                    <td>{{ $employee->phone_number ?? 'N/A' }} / {{ $employee->mobile_no ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td class="label">Email Address</td>
                    <td>{{ $employee->email }}</td>
                </tr>
            </table>
        </div>
    </div>

    <table>
        <tr>
            <td class="label">Permanent Address</td>
            <td class="sinhala">{{ $employee->permanent_address ?? $employee->address ?? 'N/A' }}</td>
        </tr>
    </table>

    <div class="section-title">2. Appointment & Career Details</div>
    <table>
        <tr>
            <td class="label">Designation</td>
            <td class="sinhala">{{ $employee->designation ?? 'N/A' }}</td>
        </tr>
        <tr>
            <td class="label">Grade</td>
            <td>{{ $employee->grade ?? 'N/A' }}</td>
        </tr>
        <tr>
            <td class="label">Section</td>
            <td>{{ $employee->section ?? 'N/A' }}</td>
        </tr>
        <tr>
            <td class="label">Current Workplace</td>
            <td class="sinhala">{{ $employee->workplace ?? 'N/A' }}</td>
        </tr>
        <tr>
            <td class="label">W&OP Number</td>
            <td>{{ $employee->wop_no ?? 'N/A' }}</td>
        </tr>
        <tr>
            <td class="label">Appointment Date</td>
            <td>{{ $employee->appointment_date ? $employee->appointment_date->format('Y-m-d') : 'N/A' }}</td>
        </tr>
        <tr>
            <td class="label">Assumed Duty Date</td>
            <td>{{ $employee->assumed_duty_date ? $employee->assumed_duty_date->format('Y-m-d') : 'N/A' }}</td>
        </tr>
    </table>

    <div class="section-title">3. Educational & Professional Qualifications</div>
    <table>
        <tr>
            <td class="label">Educational Qualifications</td>
            <td class="sinhala" style="white-space: pre-wrap;">{{ $employee->edu_qualifications ?? 'None recorded.' }}</td>
        </tr>
        <tr>
            <td class="label">Professional Qualifications</td>
            <td class="sinhala" style="white-space: pre-wrap;">{{ $employee->prof_qualifications ?? 'None recorded.' }}</td>
        </tr>
        <tr>
            <td class="label">Special Trainings</td>
            <td class="sinhala" style="white-space: pre-wrap;">{{ $employee->trainings ?? 'None recorded.' }}</td>
        </tr>
    </table>

    <div class="section-title">4. Service History & Other Details</div>
    <table>
        <tr>
            <td class="label">Detailed Service History</td>
            <td class="sinhala" style="white-space: pre-wrap;">{{ $employee->service_history ?? 'No historical data available.' }}</td>
        </tr>
        <tr>
            <td class="label">Confirmation Details</td>
            <td class="sinhala" style="white-space: pre-wrap;">{{ $employee->confirmation_details ?? 'N/A' }}</td>
        </tr>
        <tr>
            <td class="label">EB Examinations</td>
            <td class="sinhala" style="white-space: pre-wrap;">{{ $employee->eb_exams ?? 'N/A' }}</td>
        </tr>
    </table>

    <div class="footer">
        Generated on {{ date('Y-m-d H:i:s') }} | Official Personnel Information System | Zonal Education Office - Dehiovita
    </div>

</body>
</html>
