<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile Not Found - HR System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <nav class="navbar navbar-dark bg-dark mb-4">
        <div class="container text-center">
            <a class="navbar-brand mx-auto" href="#">HR Management System</a>
        </div>
    </nav>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow mt-5">
                    <div class="card-body text-center py-5">
                        <div class="mb-4">
                            <i class="fas fa-user-clock fa-4x text-warning"></i>
                        </div>
                        <h4 class="mb-3">ඔබේ පැතිකඩ තවමත් සූදානම් කර නැත <br>(Profile Not Found)</h4>
                        <p class="text-muted mb-4">
                            කරුණාකර ඔබ වෙනුවෙන් සේවක දත්ත පද්ධතියට ඇතුළත් කරන ලෙස කරුණාකර පරිපාලකවරයාගෙන් (Admin) ඉල්ලා සිටින්න.
                        </p>
                        <form method="POST" action="<?php echo e(route('logout')); ?>">
                            <?php echo csrf_field(); ?>
                            <button type="submit" class="btn btn-outline-danger btn-sm">Logout</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- FontAwesome for icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
</body>
</html>
<?php /**PATH D:\HR System\sms-final\resources\views\profile_not_found.blade.php ENDPATH**/ ?>