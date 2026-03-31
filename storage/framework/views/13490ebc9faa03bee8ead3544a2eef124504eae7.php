<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Employee - HR System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body { background: #f0f2f5; font-family: 'Segoe UI', sans-serif; }
        .page-header {
            background: linear-gradient(135deg, #1e3a5f 0%, #0d9488 100%);
            color: white; padding: 1.5rem 0; margin-bottom: 2rem;
        }
        .form-card { border: none; border-radius: 16px; box-shadow: 0 4px 24px rgba(0,0,0,0.08); }
        .nav-tabs .nav-link {
            color: #6c757d; font-weight: 600; border: none;
            padding: .75rem 1.25rem; border-bottom: 3px solid transparent;
            transition: all .2s;
        }
        .nav-tabs .nav-link.active { color: #0d9488; border-bottom-color: #0d9488; background: transparent; }
        .nav-tabs .nav-link:hover:not(.active) { color: #374151; border-bottom-color: #d1d5db; }
        .nav-tabs { border-bottom: 2px solid #e5e7eb; margin-bottom: 1.5rem; }
        .tab-icon { margin-right: .4rem; }
        .section-title {
            font-size: .7rem; text-transform: uppercase; letter-spacing: .08em;
            color: #6b7280; font-weight: 700; margin-bottom: 1rem;
            padding-bottom: .4rem; border-bottom: 1px solid #f3f4f6;
        }
        .form-label { font-weight: 600; font-size: .85rem; color: #374151; }
        .form-control, .form-select { border-radius: 8px; border-color: #d1d5db; font-size: .9rem; }
        .form-control:focus, .form-select:focus {
            border-color: #0d9488; box-shadow: 0 0 0 3px rgba(13,148,136,.1);
        }
        .readonly-field { background: #f9fafb !important; color: #6b7280; }
        .admin-badge { font-size: .65rem; background: #fef3c7; color: #92400e; padding: .15rem .4rem; border-radius: 4px; vertical-align: middle; margin-left: 4px; }
        .password-toggle {
            cursor: pointer; position: absolute; right: 14px;
            top: 50%; transform: translateY(-50%); z-index: 10; color: #9ca3af;
        }
        .photo-preview-img {
            width: 120px; height: 120px; border-radius: 50%;
            object-fit: cover; border: 4px solid #e5e7eb; margin-bottom: .75rem;
        }
        .photo-upload-area {
            border: 2px dashed #d1d5db; border-radius: 12px;
            padding: 1.5rem; text-align: center; cursor: pointer;
            transition: all .2s; background: #fafafa;
        }
        .photo-upload-area:hover { border-color: #0d9488; background: #f0fdfa; }
        .btn-nav { min-width: 120px; }
        .required-star { color: #ef4444; margin-left: 2px; }
        textarea.form-control { resize: vertical; min-height: 90px; }
    </style>
</head>
<body>

<?php $isAdmin = auth()->user()->email === 'admin@admin.com'; ?>

<div class="page-header">
    <div class="container d-flex justify-content-between align-items-center">
        <div>
            <h4 class="fw-bold mb-0">
                <i class="fas fa-user-edit me-2"></i>
                <?php echo e($isAdmin ? 'Edit Employee: ' . ($employee->full_name ?? $employee->name) : 'My HR Profile'); ?>

            </h4>
            <small class="opacity-75">
                <?php echo e($isAdmin ? 'Admin: All fields are editable.' : 'You can update personal, contact, and qualification details.'); ?>

            </small>
        </div>
        <div class="d-flex gap-2">
            <a href="<?php echo e(route('my.pdf')); ?>" class="btn btn-danger btn-sm">
                <i class="fas fa-file-pdf me-1"></i>Download PDF
            </a>
            <a href="/" class="btn btn-outline-light btn-sm">
                <i class="fas fa-arrow-left me-1"></i>Back
            </a>
        </div>
    </div>
</div>

<div class="container pb-5">

    <?php if($errors->any()): ?>
        <div class="alert alert-danger alert-dismissible fade show rounded-3 mb-4">
            <strong><i class="fas fa-exclamation-triangle me-2"></i>Please fix the following errors:</strong>
            <ul class="mb-0 mt-2">
                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li><?php echo e($error); ?></li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <form action="<?php echo e(route('employee.update', $employee->id)); ?>" method="POST" enctype="multipart/form-data" id="editForm">
        <?php echo csrf_field(); ?>

        <div class="card form-card">
            <div class="card-body p-4">

                
                <ul class="nav nav-tabs" id="editTabs" role="tablist">
                    <li class="nav-item">
                        <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#et-personal" type="button">
                            <i class="fas fa-user tab-icon"></i>Personal
                        </button>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#et-contact" type="button">
                            <i class="fas fa-phone tab-icon"></i>Contact
                        </button>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#et-professional" type="button">
                            <i class="fas fa-briefcase tab-icon"></i>Professional
                        </button>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#et-service" type="button">
                            <i class="fas fa-history tab-icon"></i>Service & Career
                        </button>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#et-quals" type="button">
                            <i class="fas fa-graduation-cap tab-icon"></i>Qualifications
                        </button>
                    </li>
                </ul>

                <div class="tab-content">

                    
                    <div class="tab-pane fade show active" id="et-personal" role="tabpanel">
                        <p class="section-title"><i class="fas fa-id-card me-1"></i>Personal Identity</p>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Full Name <span class="required-star">*</span></label>
                                <input type="text" class="form-control <?php $__errorArgs = ['full_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                       name="full_name" value="<?php echo e(old('full_name', $employee->full_name ?? $employee->name)); ?>" required>
                                <?php $__errorArgs = ['full_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Full Name with Initials</label>
                                <input type="text" class="form-control"
                                       name="full_name_with_initials" value="<?php echo e(old('full_name_with_initials', $employee->full_name_with_initials)); ?>"
                                       placeholder="e.g. A.B. Perera">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">
                                    NIC Number
                                    <?php if(!$isAdmin): ?><span class="admin-badge">Admin Only</span><?php endif; ?>
                                </label>
                                <input type="text" class="form-control <?php $__errorArgs = ['nic_number'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?> <?php echo e(!$isAdmin ? 'readonly-field' : ''); ?>"
                                       name="nic_number" value="<?php echo e(old('nic_number', $employee->nic_number)); ?>"
                                       <?php echo e(!$isAdmin ? 'readonly' : ''); ?>>
                                <?php $__errorArgs = ['nic_number'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Date of Birth</label>
                                <input type="date" class="form-control <?php $__errorArgs = ['birthday'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                       name="birthday" id="birthdayInput"
                                       value="<?php echo e(old('birthday', $employee->birthday ? $employee->birthday->format('Y-m-d') : '')); ?>">
                                <?php $__errorArgs = ['birthday'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Age</label>
                                <input type="text" class="form-control bg-light" id="ageDisplay" readonly
                                       value="<?php echo e($employee->age ? $employee->age . ' years' : ''); ?>">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Race / Religion</label>
                                <input type="text" class="form-control" name="race_religion"
                                       value="<?php echo e(old('race_religion', $employee->race_religion)); ?>">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Marital Status</label>
                                <select class="form-select <?php $__errorArgs = ['marital_status'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" name="marital_status">
                                    <option value="">-- Select --</option>
                                    <?php $__currentLoopData = ['Single','Married','Divorced','Widowed']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ms): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($ms); ?>" <?php echo e(old('marital_status', $employee->marital_status) == $ms ? 'selected' : ''); ?>><?php echo e($ms); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                                <?php $__errorArgs = ['marital_status'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Permanent Address</label>
                                <textarea class="form-control" name="permanent_address" rows="2"><?php echo e(old('permanent_address', $employee->permanent_address)); ?></textarea>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Current Address</label>
                                <textarea class="form-control" name="address" rows="2"><?php echo e(old('address', $employee->address)); ?></textarea>
                            </div>
                        </div>

                        <hr class="my-4">
                        <p class="section-title"><i class="fas fa-camera me-1"></i>Profile Photo</p>

                        <div class="d-flex align-items-center gap-4 flex-wrap">
                            <?php if($employee->profile_photo_path): ?>
                                <img src="<?php echo e(asset('storage/' . $employee->profile_photo_path)); ?>"
                                     class="photo-preview-img" id="photoPreview" alt="Current Photo">
                            <?php else: ?>
                                <img id="photoPreview" class="photo-preview-img" src="" alt="Preview" style="display:none">
                            <?php endif; ?>
                            <div class="flex-grow-1">
                                <div class="photo-upload-area" onclick="document.getElementById('photoInput').click()">
                                    <i class="fas fa-camera fa-2x text-muted mb-2"></i>
                                    <p class="mb-1 fw-semibold">
                                        <?php echo e($employee->profile_photo_path ? 'Click to change photo' : 'Click to upload photo'); ?>

                                    </p>
                                    <small class="text-muted">JPEG, PNG, GIF — max 3 MB</small>
                                </div>
                                <input type="file" id="photoInput" name="photo" accept="image/*" class="d-none" onchange="previewPhoto(this)">
                            </div>
                        </div>
                    </div>

                    
                    <div class="tab-pane fade" id="et-contact" role="tabpanel">
                        <p class="section-title"><i class="fas fa-envelope me-1"></i>Login / Account</p>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">
                                    Email / Username <span class="required-star">*</span>
                                    <?php if(!$isAdmin): ?><span class="admin-badge">Admin Only</span><?php endif; ?>
                                </label>
                                <input type="email" class="form-control <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?> <?php echo e(!$isAdmin ? 'readonly-field' : ''); ?>"
                                       name="email" value="<?php echo e(old('email', $employee->email)); ?>"
                                       <?php echo e(!$isAdmin ? 'readonly' : ''); ?> required>
                                <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                <?php if(!$isAdmin): ?><small class="text-muted">Email cannot be changed by users.</small><?php endif; ?>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Change Password</label>
                                <div class="position-relative">
                                    <input type="password" class="form-control" id="passwordInput" name="password"
                                           placeholder="Leave blank to keep current password">
                                    <span class="password-toggle" onclick="togglePassword('passwordInput', this)">
                                        <i class="fas fa-eye"></i>
                                    </span>
                                </div>
                                <small class="text-muted">Leave blank to keep the current password.</small>
                            </div>
                        </div>

                        <hr class="my-4">
                        <p class="section-title"><i class="fas fa-phone me-1"></i>Phone Numbers</p>
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label">Office / Home Phone</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-phone-alt"></i></span>
                                    <input type="text" class="form-control <?php $__errorArgs = ['phone_number'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                           name="phone_number" value="<?php echo e(old('phone_number', $employee->phone_number)); ?>">
                                </div>
                                <?php $__errorArgs = ['phone_number'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="text-danger small mt-1"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Mobile No</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-mobile-alt"></i></span>
                                    <input type="text" class="form-control <?php $__errorArgs = ['mobile_no'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                           name="mobile_no" value="<?php echo e(old('mobile_no', $employee->mobile_no)); ?>">
                                </div>
                                <?php $__errorArgs = ['mobile_no'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="text-danger small mt-1"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">WhatsApp No</label>
                                <div class="input-group">
                                    <span class="input-group-text text-success"><i class="fab fa-whatsapp"></i></span>
                                    <input type="text" class="form-control <?php $__errorArgs = ['whatsapp_no'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                           name="whatsapp_no" value="<?php echo e(old('whatsapp_no', $employee->whatsapp_no)); ?>">
                                </div>
                                <?php $__errorArgs = ['whatsapp_no'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="text-danger small mt-1"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                        </div>
                    </div>

                    
                    <div class="tab-pane fade" id="et-professional" role="tabpanel">
                        <p class="section-title"><i class="fas fa-briefcase me-1"></i>Position & Placement</p>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Designation <span class="required-star">*</span></label>
                                <input type="text" class="form-control"
                                       name="designation" value="<?php echo e(old('designation', $employee->designation)); ?>" required>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Grade</label>
                                <input type="text" class="form-control" name="grade" value="<?php echo e(old('grade', $employee->grade)); ?>">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Section</label>
                                <input type="text" class="form-control" name="section" value="<?php echo e(old('section', $employee->section)); ?>">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Workplace / Station</label>
                                <input type="text" class="form-control" name="workplace" value="<?php echo e(old('workplace', $employee->workplace)); ?>">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">WOP No</label>
                                <input type="text" class="form-control" name="wop_no" value="<?php echo e(old('wop_no', $employee->wop_no)); ?>">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">First Appointment Date</label>
                                <input type="date" class="form-control" name="appointment_date"
                                       value="<?php echo e(old('appointment_date', $employee->appointment_date ? $employee->appointment_date->format('Y-m-d') : '')); ?>">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Assumed Duty Date (Current Post)</label>
                                <input type="date" class="form-control" name="assumed_duty_date"
                                       value="<?php echo e(old('assumed_duty_date', $employee->assumed_duty_date ? $employee->assumed_duty_date->format('Y-m-d') : '')); ?>">
                            </div>
                        </div>
                    </div>

                    
                    <div class="tab-pane fade" id="et-service" role="tabpanel">
                        <div class="row g-4">
                            <div class="col-12">
                                <p class="section-title">Service History</p>
                                <textarea class="form-control" name="service_history" rows="5"><?php echo e(old('service_history', $employee->service_history)); ?></textarea>
                            </div>
                            <div class="col-md-6">
                                <p class="section-title">Current Office Details</p>
                                <textarea class="form-control" name="current_office_details" rows="4"><?php echo e(old('current_office_details', $employee->current_office_details)); ?></textarea>
                            </div>
                            <div class="col-md-6">
                                <p class="section-title">Confirmation Details</p>
                                <textarea class="form-control" name="confirmation_details" rows="4"><?php echo e(old('confirmation_details', $employee->confirmation_details)); ?></textarea>
                            </div>
                            <div class="col-12">
                                <p class="section-title">EB Exams</p>
                                <textarea class="form-control" name="eb_exams" rows="4"><?php echo e(old('eb_exams', $employee->eb_exams)); ?></textarea>
                            </div>
                        </div>
                    </div>

                    
                    <div class="tab-pane fade" id="et-quals" role="tabpanel">
                        <div class="row g-4">
                            <div class="col-12">
                                <p class="section-title">Educational Qualifications</p>
                                <textarea class="form-control" name="edu_qualifications" rows="4"><?php echo e(old('edu_qualifications', $employee->edu_qualifications)); ?></textarea>
                            </div>
                            <div class="col-12">
                                <p class="section-title">Professional Qualifications</p>
                                <textarea class="form-control" name="prof_qualifications" rows="4"><?php echo e(old('prof_qualifications', $employee->prof_qualifications)); ?></textarea>
                            </div>
                            <div class="col-12">
                                <p class="section-title">Trainings & Workshops</p>
                                <textarea class="form-control" name="trainings" rows="4"><?php echo e(old('trainings', $employee->trainings)); ?></textarea>
                            </div>
                        </div>
                    </div>

                </div>

                
                <hr class="mt-4 mb-3">
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <a href="/" class="btn btn-outline-secondary btn-nav"><i class="fas fa-times me-1"></i>Cancel</a>
                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-light btn-nav" id="prevTabBtn" onclick="switchTab(-1)" disabled style="display:none">
                            <i class="fas fa-chevron-left me-1"></i>Previous
                        </button>
                        <button type="button" class="btn btn-outline-primary btn-nav" id="nextTabBtn" onclick="switchTab(1)">
                            Next <i class="fas fa-chevron-right ms-1"></i>
                        </button>
                        <button type="submit" class="btn btn-success btn-nav" id="submitBtn" style="display:none">
                            <i class="fas fa-save me-1"></i>Update Profile
                        </button>
                    </div>
                </div>

            </div>
        </div>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    const tabs = ['et-personal','et-contact','et-professional','et-service','et-quals'];
    let currentTab = 0;

    function switchTab(dir) {
        const next = currentTab + dir;
        if (next < 0 || next >= tabs.length) return;
        bootstrap.Tab.getOrCreateInstance(document.querySelector(`[data-bs-target="#${tabs[next]}"]`)).show();
        currentTab = next;
        updateNavButtons();
    }

    function updateNavButtons() {
        document.getElementById('prevTabBtn').style.display = currentTab === 0 ? 'none' : '';
        document.getElementById('nextTabBtn').style.display = currentTab === tabs.length - 1 ? 'none' : '';
        document.getElementById('submitBtn').style.display  = currentTab === tabs.length - 1 ? '' : 'none';
    }

    document.querySelectorAll('#editTabs .nav-link').forEach((btn, idx) => {
        btn.addEventListener('shown.bs.tab', () => { currentTab = idx; updateNavButtons(); });
    });

    function togglePassword(inputId, el) {
        const input = document.getElementById(inputId);
        const icon = el.querySelector('i');
        input.type = input.type === 'password' ? 'text' : 'password';
        icon.classList.toggle('fa-eye');
        icon.classList.toggle('fa-eye-slash');
    }

    function previewPhoto(input) {
        const preview = document.getElementById('photoPreview');
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = e => { preview.src = e.target.result; preview.style.display = 'block'; };
            reader.readAsDataURL(input.files[0]);
        }
    }

    // Age calculator
    document.getElementById('birthdayInput').addEventListener('change', function() {
        const dob = new Date(this.value);
        if (!isNaN(dob)) {
            const today = new Date();
            let age = today.getFullYear() - dob.getFullYear();
            const m = today.getMonth() - dob.getMonth();
            if (m < 0 || (m === 0 && today.getDate() < dob.getDate())) age--;
            document.getElementById('ageDisplay').value = age + ' years';
        }
    });

    updateNavButtons();
</script>
</body>
</html>
<?php /**PATH D:\HR System\sms-final\resources\views\update.blade.php ENDPATH**/ ?>