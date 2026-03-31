<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Leave Application - <?php echo e($user->full_name ?? $user->name); ?></title>
    <style>
        @page {
            margin: 2cm;
            size: A4;
        }
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            line-height: 1.6;
            color: #333;
            font-size: 12px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #444;
            padding-bottom: 10px;
        }
        .header h1 {
            font-size: 18px;
            margin: 0;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .header p {
            margin: 5px 0 0;
            font-weight: bold;
            color: #666;
        }
        .section-title {
            background-color: #f2f2f2;
            padding: 5px 10px;
            font-weight: bold;
            margin-top: 20px;
            margin-bottom: 10px;
            border-left: 4px solid #333;
            text-transform: uppercase;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        table td {
            padding: 8px;
            vertical-align: top;
            border: 1px solid #ddd;
        }
        .label {
            font-weight: bold;
            width: 35%;
            background-color: #fafafa;
        }
        .signature-section {
            margin-top: 40px;
        }
        .sig-box {
            width: 100%;
            margin-top: 30px;
        }
        .sig-line {
            border-top: 1px solid #000;
            width: 200px;
            margin-top: 50px;
            text-align: center;
        }
        .sig-label {
            font-size: 10px;
            margin-top: 5px;
        }
        .footer {
            position: fixed;
            bottom: 0;
            width: 100%;
            text-align: center;
            font-size: 9px;
            color: #999;
            border-top: 1px solid #eee;
            padding-top: 5px;
        }
        .balance-table {
            text-align: center;
        }
        .balance-table th {
            background-color: #f9f9f9;
            padding: 8px;
            border: 1px solid #ddd;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Leave Application - Education Administrative Service</h1>
        <p>නිවාඩු අයදුම්පත - අධ්‍යාපන පරිපාලන සේවය</p>
    </div>

    <div class="section-title">1. Applicant Details (අයදුම්කරුගේ තොරතුරු)</div>
    <table>
        <tr>
            <td class="label">Full Name (සම්පූර්ණ නම)</td>
            <td><?php echo e($user->full_name ?? $user->name); ?></td>
        </tr>
        <tr>
            <td class="label">NIC Number (හැඳුනුම්පත් අංකය)</td>
            <td><?php echo e($user->nic_number ?? 'N/A'); ?></td>
        </tr>
        <tr>
            <td class="label">Designation (තනතුර)</td>
            <td><?php echo e($user->designation ?? 'N/A'); ?></td>
        </tr>
        <tr>
            <td class="label">Workplace (වැඩකරන ස්ථානය)</td>
            <td><?php echo e($user->workplace ?? 'N/A'); ?></td>
        </tr>
    </table>

    <div class="section-title">2. Leave Particulars (නිවාඩු විස්තර)</div>
    <table>
        <tr>
            <td class="label">Leave Type (නිවාඩු වර්ගය)</td>
            <td><?php echo e($leave->leave_type); ?></td>
        </tr>
        <tr>
            <td class="label">Period (කාලසීමාව)</td>
            <td>From <strong><?php echo e($leave->start_date); ?></strong> To <strong><?php echo e($leave->end_date); ?></strong></td>
        </tr>
        <tr>
            <td class="label">Net Business Days (වැඩ කරන දින ගණන)</td>
            <td><strong><?php echo e($leave->requested_days); ?></strong> days</td>
        </tr>
        <tr>
            <td class="label">Return to Work Date (වැඩට වාර්තා කරන දිනය)</td>
            <td><strong><?php echo e($return_date); ?></strong></td>
        </tr>
        <tr>
            <td class="label">Reason (හේතුව)</td>
            <td><?php echo e($leave->reason); ?></td>
        </tr>
    </table>

    <div class="section-title">3. Current Leave Balance (පවතින නිවාඩු ශේෂය - <?php echo e(date('Y')); ?>)</div>
    <table class="balance-table">
        <thead>
            <tr>
                <th>Leave Type</th>
                <th>Used (පාවිච්චි කළ)</th>
                <th>Limit (සීමාව)</th>
                <th>Remaining (ඉතිරි)</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Sick Leave (අසනීප)</td>
                <td><?php echo e($balances['Sick Leave']['used']); ?></td>
                <td>21</td>
                <td><?php echo e(21 - $balances['Sick Leave']['used']); ?></td>
            </tr>
            <tr>
                <td>Casual Leave (අනියම්)</td>
                <td><?php echo e($balances['Casual Leave']['used']); ?></td>
                <td>24</td>
                <td><?php echo e(24 - $balances['Casual Leave']['used']); ?></td>
            </tr>
            <tr>
                <td>Annual Leave (වාර්ෂික)</td>
                <td><?php echo e($balances['Annual Leave']['used']); ?></td>
                <td>45</td>
                <td><?php echo e(45 - $balances['Annual Leave']['used']); ?></td>
            </tr>
        </tbody>
    </table>

    <div class="signature-section">
        <table border="0" style="border: none;">
            <tr style="border: none;">
                <td style="border: none; width: 50%;">
                    <div class="sig-line"></div>
                    <div class="sig-label">Applicant's Signature<br>(අයදුම්කරුගේ අත්සන)</div>
                </td>
                <td style="border: none; width: 50%; text-align: right;">
                    <div style="display: inline-block; text-align: center;">
                        <div class="sig-line" style="margin-right: 0; margin-left: auto;"></div>
                        <div class="sig-label">Date (දිනය)</div>
                    </div>
                </td>
            </tr>
        </table>

        <div class="section-title" style="margin-top: 40px;">4. Recommendation & Approval (නිර්දේශය සහ අනුමැතිය)</div>
        <table border="0" style="border: none; margin-top: 20px;">
            <tr style="border: none;">
                <td style="border: none; padding-top: 30px;">
                    <p>Recommended / Not Recommended</p>
                    <div class="sig-line"></div>
                    <div class="sig-label">Head of Department / Recommending Officer<br>(අංශ ප්‍රධානී / නිර්දේශ කරන නිලධාරියා)</div>
                </td>
                <td style="border: none; padding-top: 30px; text-align: right;">
                    <p>Approved / Not Approved</p>
                    <div class="sig-line" style="margin-right: 0; margin-left: auto;"></div>
                    <div class="sig-label">Sanctioning Officer / Approval<br>(අනුමත කරන නිලධාරියා)</div>
                </td>
            </tr>
        </table>
    </div>

    <div class="footer">
        Generated by HR Management System on <?php echo e(date('Y-m-d H:i:s')); ?> | Official Document
    </div>
</body>
</html>
<?php /**PATH D:\HR System\sms-final\resources\views\leaves\leave_application_pdf.blade.php ENDPATH**/ ?>