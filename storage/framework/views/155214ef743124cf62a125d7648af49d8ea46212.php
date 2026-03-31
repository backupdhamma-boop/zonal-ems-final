<!DOCTYPE html>
<html>
<head>
    <title>Leave Summary Report - <?php echo e($year); ?>/<?php echo e($month); ?></title>
    <style>
        body { font-family: sans-serif; }
        h1 { text-align: center; }
        h3 { text-align: center; color: #666; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 12px; text-align: left; }
        th { bg-color: #f2f2f2; }
        .text-center { text-align: center; }
        .footer { position: fixed; bottom: 0; width: 100%; text-align: center; font-size: 10px; color: #777; }
        .badge { font-weight: bold; border-radius: 4px; padding: 2px 6px; }
    </style>
</head>
<body>
    <h1>SMS - Leave Summary Report</h1>
    <h3>Date Range: <?php echo e(date('F', mktime(0, 0, 0, $month, 1))); ?> <?php echo e($year); ?></h3>

    <table>
        <thead>
            <tr>
                <th>Employee Name</th>
                <th>Employee ID</th>
                <th>Sick Leave</th>
                <th>Casual Leave</th>
                <th>Annual Leave (Used/45)</th>
                <th>Duty Leave</th>
            </tr>
        </thead>
        <tbody>
            <?php $__currentLoopData = $summaryData; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr>
                <td><?php echo e($data['name']); ?></td>
                <td>EMP-<?php echo e(str_pad($data['employee_id'], 4, '0', STR_PAD_LEFT)); ?></td>
                <td class="text-center"><?php echo e($data['sick']); ?> / 21</td>
                <td class="text-center"><?php echo e($data['casual']); ?> / 24</td>
                <td class="text-center"><strong><?php echo e($data['annual']); ?> / 45</strong></td>
                <td class="text-center"><?php echo e($data['duty']); ?></td>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
    </table>

    <div class="footer">
        Generated on: <?php echo e(date('Y-m-d H:i:s')); ?>

    </div>
</body>
</html>
<?php /**PATH D:\HR System\sms-final\resources\views\leaves\summary_pdf.blade.php ENDPATH**/ ?>