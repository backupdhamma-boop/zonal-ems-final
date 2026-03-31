<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Zonal Annual Staff Leave Summary - <?php echo e($year); ?></title>
    <style>
        @page {
            margin: 1cm;
            size: A4 landscape;
        }
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 10px;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header h1 {
            margin: 0;
            text-transform: uppercase;
            font-size: 18px;
            color: #1a202c;
        }
        .header p {
            margin: 5px 0;
            font-size: 14px;
            font-weight: bold;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #dee2e6;
            padding: 8px 10px;
            text-align: left;
        }
        th {
            background-color: #f8f9fa;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 9px;
        }
        .text-center { text-align: center; }
        .footer {
            margin-top: 50px;
        }
        .sig-container {
            width: 100%;
        }
        .sig-box {
            width: 30%;
            display: inline-block;
            vertical-align: top;
        }
        .sig-line {
            border-top: 1px solid #000;
            width: 80%;
            margin-bottom: 5px;
        }
        .bg-totals {
            background-color: #f1f5f9;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Zonal Annual Staff Leave Summary - <?php echo e($year); ?></h1>
        <p>ZONAL EDUCATION OFFICE - DEHIOVITA</p>
        <div style="font-size: 11px; margin-top: 5px;">Generated on: <?php echo e(date('Y-m-d H:i')); ?></div>
    </div>

    <table>
        <thead>
            <tr>
                <th width="30">#</th>
                <th>Name / නම</th>
                <th width="100">NIC / හැඳුනුම් අංකය</th>
                <th>Designation / තනතුර</th>
                <th width="60" class="text-center">Casual (24)</th>
                <th width="60" class="text-center">Sick (21)</th>
                <th width="60" class="text-center">Total (45)</th>
            </tr>
        </thead>
        <tbody>
            <?php $__currentLoopData = $reportData; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr>
                <td class="text-center"><?php echo e($index + 1); ?></td>
                <td><?php echo e($data['name']); ?></td>
                <td><?php echo e($data['nic']); ?></td>
                <td><?php echo e($data['designation']); ?></td>
                <td class="text-center"><?php echo e($data['casual']); ?></td>
                <td class="text-center"><?php echo e($data['sick']); ?></td>
                <td class="text-center fw-bold"><?php echo e($data['annual']); ?></td>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
    </table>

    <div class="footer">
        <div class="sig-container">
            <div class="sig-box">
                <div class="sig-line"></div>
                <p>Prepared By:<br>Subject Clerk</p>
            </div>
            <div class="sig-box">
                <div class="sig-line"></div>
                <p>Checked By:<br>Administrative Officer</p>
            </div>
            <div class="sig-box">
                <div class="sig-line"></div>
                <p>Approved By:<br>Zonal Director of Education</p>
            </div>
        </div>
    </div>
</body>
</html>
<?php /**PATH D:\HR System\sms-final\resources\views\leaves\zonal_annual_summary_pdf.blade.php ENDPATH**/ ?>