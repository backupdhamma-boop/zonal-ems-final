<!DOCTYPE html>
<html>
<head>
    <title>Employee List</title>
    <style>
        body { font-family: sans-serif; font-size: 14px; }
        h2 { text-align: center; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        table, th, td { border: 1px solid black; padding: 8px; }
        th { background-color: #f2f2f2; text-align: left; }
    </style>
</head>
<body>
    <h2>Employee List (සේවක ලැයිස්තුව)</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Full Name</th>
                <th>Designation</th>
                <th>Salary (Rs)</th>
            </tr>
        </thead>
        <tbody>
            <?php $__empty_1 = true; $__currentLoopData = $employees; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $employee): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <tr>
                <td><?php echo e($employee->id); ?></td>
                <td><?php echo e($employee->full_name); ?></td>
                <td><?php echo e($employee->designation); ?></td>
                <td><?php echo e(number_format($employee->salary, 2)); ?></td>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <tr>
                <td colspan="4" style="text-align:center;">No Employees Found</td>
            </tr>
            <?php endif; ?>
        </tbody>
    </table>
</body>
</html>
<?php /**PATH D:\HR System\sms-final\resources\views\pdf\employees.blade.php ENDPATH**/ ?>