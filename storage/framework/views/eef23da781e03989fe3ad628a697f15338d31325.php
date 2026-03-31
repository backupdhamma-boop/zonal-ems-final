<?php $__env->startSection('header'); ?>
    <div class="flex justify-between items-center">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            <?php echo e(__('Update My Profile - මගේ තොරතුරු යාවත්කාලීන කිරීම')); ?>

        </h2>
        <a href="<?php echo e(route('leaves.index')); ?>" class="text-indigo-600 hover:text-indigo-900 font-bold">
            <i class="fas fa-arrow-left mr-1"></i> Back to Dashboard
        </a>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="py-12">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-8 bg-white border-b border-gray-200">
                
                <form method="POST" action="<?php echo e(route('employee.update', $employee->id)); ?>" enctype="multipart/form-data">
                    <?php echo csrf_field(); ?>
                    <!-- We use POST with hidden method if needed, but employee.update handles it -->

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
                        <!-- Profile Photo Upload -->
                        <div class="flex flex-col items-center">
                            <?php if($employee->photo): ?>
                                <img src="<?php echo e(asset('storage/' . $employee->photo)); ?>" class="rounded-full w-40 h-40 object-cover border-4 border-indigo-100 mb-4 shadow-sm">
                            <?php else: ?>
                                <div class="rounded-full w-40 h-40 bg-indigo-50 flex items-center justify-center text-indigo-300 text-5xl mb-4">
                                    <i class="fas fa-user"></i>
                                </div>
                            <?php endif; ?>
                            
                            <label class="block mb-2 text-sm font-bold text-gray-700">Change Profile Photo</label>
                            <input type="file" name="photo" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                            <p class="mt-1 text-xs text-gray-400">JPG, PNG strictly (Max 2MB)</p>
                        </div>

                        <!-- Read-only Official Data -->
                        <div class="space-y-4">
                            <div class="p-4 bg-gray-50 rounded-lg border border-gray-100">
                                <h4 class="text-xs font-black text-gray-400 uppercase tracking-widest mb-3">Official Status (Read-Only)</h4>
                                <div class="space-y-3">
                                    <div>
                                        <span class="block text-xs font-bold text-gray-500">Designation</span>
                                        <span class="text-gray-700 font-semibold"><?php echo e($employee->designation); ?></span>
                                    </div>
                                    <div>
                                        <span class="block text-xs font-bold text-gray-500">Official Email</span>
                                        <span class="text-gray-700 font-semibold"><?php echo e($employee->user->email); ?></span>
                                    </div>
                                    <div>
                                        <span class="block text-xs font-bold text-gray-500">Workplace</span>
                                        <span class="text-gray-700 font-semibold"><?php echo e($employee->workplace); ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr class="mb-8 border-gray-100">

                    <div class="grid grid-cols-1 gap-6 mb-8">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1">Full Name (සම්පූර්ණ නම)</label>
                            <input type="text" name="full_name" value="<?php echo e(old('full_name', $employee->full_name)); ?>" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-1">New Password (අලුත් මුරපදය)</label>
                                <input type="password" name="password" placeholder="Leave blank to keep current" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-1">Confirm Password</label>
                                <input type="password" name="password_confirmation" placeholder="Confirm new password" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center justify-end border-t pt-6 gap-4">
                        <a href="<?php echo e(route('leaves.index')); ?>" class="text-sm font-bold text-gray-500 hover:text-gray-800">Cancel</a>
                        <button type="submit" class="inline-flex items-center px-6 py-3 bg-indigo-600 border border-transparent rounded-md font-bold text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 transition duration-150 ease-in-out shadow-md">
                            <i class="fas fa-save mr-2"></i> Save Changes
                        </button>
                    </div>
                </form>

            </div>
        </div>

    </div>
</div>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\HR System\sms-final\resources\views\employees\edit_profile.blade.php ENDPATH**/ ?>