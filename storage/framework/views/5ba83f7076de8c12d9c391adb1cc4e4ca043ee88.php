<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Leave Request - HR System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-light">

    <nav class="navbar navbar-dark bg-dark mb-4">
        <div class="container text-center">
            <a class="navbar-brand mx-auto" href="/">HR Management System</a>
        </div>
    </nav>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow">
                    <div class="card-header bg-warning">
                        <h4 class="mb-0 text-dark">නිවාඩු අයදුම්පත සංස්කරණය (Edit Leave Request)</h4>
                    </div>
                    <div class="card-body">
                        <?php if($errors->any()): ?>
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <li><?php echo e($error); ?></li>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </ul>
                            </div>
                        <?php endif; ?>

                        <form action="<?php echo e(route('leaves.update', $leave->id)); ?>" method="POST">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('PUT'); ?>
                            
                            <div class="mb-3">
                                <label for="leave_type" class="form-label">නිවාඩු වර්ගය (Leave Type)</label>
                                <select name="leave_type" id="leave_type" class="form-select" required>
                                    <option value="Casual Leave" <?php echo e($leave->leave_type == 'Casual Leave' ? 'selected' : ''); ?>>අනියම් නිවාඩු (Casual Leave)</option>
                                    <option value="Sick Leave" <?php echo e($leave->leave_type == 'Sick Leave' ? 'selected' : ''); ?>>අසනීප නිවාඩු (Sick Leave)</option>
                                    <option value="Duty Leave" <?php echo e($leave->leave_type == 'Duty Leave' ? 'selected' : ''); ?>>රාජකාරි නිවාඩු (Duty Leave)</option>
                                    <option value="Annual Leave" <?php echo e($leave->leave_type == 'Annual Leave' ? 'selected' : ''); ?>>වාර්ෂික නිවාඩු (Annual Leave)</option>
                                </select>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="start_date" class="form-label">සිට (Start Date)</label>
                                    <input type="date" name="start_date" id="start_date" class="form-control" value="<?php echo e($leave->start_date); ?>" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="end_date" class="form-label">දක්වා (Return to Work Date)</label>
                                    <input type="date" name="end_date" id="end_date" class="form-control" value="<?php echo e($leave->end_date); ?>" required>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="requested_days" class="form-label">නිවාඩු දින ගණන (Number of Days)</label>
                                <input type="number" name="requested_days" id="requested_days" class="form-control" step="0.5" min="0.5" value="<?php echo e($leave->requested_days); ?>" required>
                                <small class="text-muted">Dates used for auto-calculation; manually edit for half-days.</small>
                            </div>

                            <div class="mb-3">
                                <label for="reason" class="form-label">හේතුව (Reason/Details)</label>
                                <textarea name="reason" id="reason" rows="3" class="form-control" required><?php echo e($leave->reason); ?></textarea>
                            </div>

                            <div class="d-flex justify-content-between">
                                <a href="<?php echo e(route('leaves.index')); ?>" class="btn btn-secondary">ආපසු (Back)</a>
                                <button type="submit" class="btn btn-warning">යාවත්කාලීන කරන්න (Update Application)</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const startDate = document.getElementById('start_date');
            const endDate = document.getElementById('end_date');
            const requestedDays = document.getElementById('requested_days');
            const holidayDates = <?php echo json_encode($holidayDates ?? [], 15, 512) ?>;

            function calculateDays() {
                if (startDate.value && endDate.value) {
                    let start = new Date(startDate.value);
                    let end = new Date(endDate.value);
                    
                    if (end > start) {
                        let count = 0;
                        let curDate = new Date(start);
                        
                        while (curDate < end) {
                            let dayOfWeek = curDate.getDay();
                            // Use en-CA to safely get YYYY-MM-DD
                            let dateString = curDate.toLocaleDateString('en-CA');
                            
                            // 0 = Sunday, 6 = Saturday
                            if (dayOfWeek !== 0 && dayOfWeek !== 6 && !holidayDates.includes(dateString)) {
                                count++;
                            }
                            curDate.setDate(curDate.getDate() + 1);
                        }
                        requestedDays.value = count;
                    } else if (startDate.value === endDate.value) {
                        requestedDays.value = 0.5;
                    }
                }
            }

            startDate.addEventListener('change', calculateDays);
            endDate.addEventListener('change', calculateDays);
        });
    </script>
</body>
</html>
<?php /**PATH D:\HR System\sms-final\resources\views\leaves\edit.blade.php ENDPATH**/ ?>