<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Annual Leave Summary - <?php echo e($user->full_name ?? $user->name); ?></title>
    <style>
        @page {
            margin: 1.5cm;
            size: A4;
        }
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            line-height: 1.4;
            color: #333;
            font-size: 11px;
        }
        .header {
            text-align: center;
            margin-bottom: 25px;
            border-bottom: 2px double #444;
            padding-bottom: 10px;
        }
        .header h1 {
            font-size: 18px;
            margin: 0;
            text-transform: uppercase;
        }
        .header h2 {
            font-size: 14px;
            margin: 5px 0 0;
            color: #555;
        }
        .employee-info {
            margin-bottom: 20px;
            padding: 10px;
            background-color: #f9f9f9;
            border: 1px solid #ddd;
        }
        .employee-info table {
            border: none;
            width: 100%;
        }
        .employee-info td {
            border: none;
            padding: 2px 5px;
        }
        .label {
            font-weight: bold;
            width: 15%;
        }
        table.main-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table.main-table th, table.main-table td {
            border: 1px solid #000;
            padding: 6px;
            text-align: left;
        }
        table.main-table th {
            background-color: #f2f2f2;
            text-transform: uppercase;
            font-size: 10px;
        }
        .text-center { text-align: center !important; }
        .summary-footer {
            margin-top: 20px;
            padding: 10px;
            border: 1px solid #333;
        }
        .summary-footer h3 {
            margin: 0 0 10px 0;
            font-size: 12px;
            text-decoration: underline;
        }
        .stat-grid {
            width: 100%;
        }
        .stat-item {
            font-size: 11px;
            font-weight: bold;
        }
        .signature-area {
            margin-top: 60px;
        }
        .sig-box {
            width: 45%;
            display: inline-block;
            vertical-align: top;
        }
        .sig-line {
            border-top: 1px solid #000;
            width: 200px;
            margin-top: 40px;
        }
        .sig-text {
            font-size: 10px;
            margin-top: 5px;
            font-weight: bold;
        }
        .footer-note {
            position: fixed;
            bottom: 0;
            width: 100%;
            text-align: center;
            font-size: 8px;
            color: #777;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>ANNUAL LEAVE SUMMARY - <?php echo e($year); ?></h1>
        <h2>ZONAL EDUCATION OFFICE - DEHIOVITA</h2>
    </div>

    <div class="employee-info">
        <table>
            <tr>
                <td class="label">Name:</td>
                <td><?php echo e($user->full_name ?? $user->name); ?></td>
                <td class="label">NIC:</td>
                <td><?php echo e($user->nic_number ?? 'N/A'); ?></td>
            </tr>
            <tr>
                <td class="label">Designation:</td>
                <td><?php echo e($user->designation ?? 'SLEAS'); ?></td>
                <td class="label">Workplace:</td>
                <td><?php echo e($user->workplace ?? 'N/A'); ?></td>
            </tr>
        </table>
    </div>

    <table class="main-table">
        <thead>
            <tr>
                <th width="5%" class="text-center">#</th>
                <th width="15%">Date From</th>
                <th width="15%">Date To</th>
                <th width="10%" class="text-center">Days</th>
                <th width="15%">Type</th>
                <th>Reason</th>
                <th width="10%">Status</th>
            </tr>
        </thead>
        <tbody>
            <?php $__empty_1 = true; $__currentLoopData = $leaves; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $leave): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <tr>
                <td class="text-center"><?php echo e($index + 1); ?></td>
                <td><?php echo e($leave->start_date); ?></td>
                <td><?php echo e($leave->end_date); ?></td>
                <td class="text-center"><?php echo e($leave->requested_days); ?></td>
                <td><?php echo e($leave->leave_type); ?></td>
                <td><?php echo e($leave->reason); ?></td>
                <td><?php echo e($leave->status); ?></td>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <tr>
                <td colspan="7" class="text-center">No leave records found for the year <?php echo e($year); ?>.</td>
            </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <div class="summary-footer">
        <h3>Summary of Leaves Taken:</h3>
        <table class="stat-grid">
            <tr>
                <td class="stat-item">Total Casual Leaves Used (අනියම්):</td>
                <td class="stat-item text-center"><?php echo e($balances['Casual Leave']['used']); ?> / 24</td>
            </tr>
            <tr>
                <td class="stat-item">Total Sick Leaves Used (අසනීප):</td>
                <td class="stat-item text-center"><?php echo e($balances['Sick Leave']['used']); ?> / 21</td>
            </tr>
            <tr>
                <td class="stat-item">Total Annual Leaves Used (වාර්ෂික):</td>
                <td class="stat-item text-center"><?php echo e($balances['Annual Leave']['used']); ?> / 45</td>
            </tr>
        </table>
    </div>

    <div class="signature-area">
        <div class="sig-box" style="float: left;">
            <div class="sig-line"></div>
            <div class="sig-text">Subject Clerk (විෂය ලිපිකරු)<br>Date: ........................</div>
        </div>
        <div class="sig-box" style="float: right; text-align: right;">
            <div class="sig-line" style="margin-left: auto;"></div>
            <div class="sig-text">Zonal Director / Admin Officer<br>Date: ........................</div>
        </div>
        <div style="clear: both;"></div>
    </div>

    <div class="footer-note">
        System Generated Document | Zonal Education Office, Dehiovita | <?php echo e(date('Y-m-d H:i:s')); ?>

    </div>
</body>
</html>
<?php /**PATH D:\HR System\sms-final\resources\views\leaves\my_leave_summary_pdf.blade.php ENDPATH**/ ?>