<?php $__env->startSection('header'); ?>
    <div class="flex justify-between items-center">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            <?php if(Auth::user()->email === 'admin@admin.com'): ?>
                <?php echo e(__('Leave Administration - නිවාඩු කළමනාකරණය')); ?>

            <?php else: ?>
                <?php echo e(__('My Dashboard')); ?>

            <?php endif; ?>
        </h2>
        <?php if(Auth::user()->email === 'admin@admin.com'): ?>
            <div class="flex gap-2">
                <a href="<?php echo e(route('leaves.summary')); ?>" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring ring-indigo-300 transition duration-150 ease-in-out shadow-sm">
                    <i class="fas fa-chart-line mr-2"></i> Summary Dashboard
                </a>
                <a href="<?php echo e(route('holidays.index')); ?>" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 transition duration-150 ease-in-out shadow-sm">
                    <i class="fas fa-calendar-alt mr-2"></i> Public Holidays
                </a>
            </div>
        <?php endif; ?>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="py-12" x-data="{ tab: 'leave_details' }">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        
        <?php if(Auth::user()->email !== 'admin@admin.com'): ?>
            <!-- ================= USER DASHBOARD (TABBED LAYOUT) ================= -->
            
            <!-- Profile Header Section -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="flex flex-col md:flex-row items-center gap-6">
                        <!-- Profile Photo -->
                        <div class="relative">
                            <?php if(Auth::user()->profile_photo_path): ?>
                                <img src="<?php echo e(asset('storage/' . Auth::user()->profile_photo_path)); ?>" class="rounded-full w-32 h-32 object-cover border-4 border-indigo-100 shadow-md">
                            <?php else: ?>
                                <div class="rounded-full w-32 h-32 bg-indigo-100 flex items-center justify-center text-indigo-500 text-4xl font-bold shadow-md">
                                    <?php echo e(substr(Auth::user()->name, 0, 1)); ?>

                                </div>
                            <?php endif; ?>
                        </div>

                        <!-- Profile Basic Info -->
                        <div class="text-center md:text-left min-w-[250px]">
                            <h1 class="text-2xl font-bold text-gray-800"><?php echo e(Auth::user()->full_name ?? Auth::user()->name); ?></h1>
                            <p class="text-indigo-600 font-semibold text-lg"><?php echo e(Auth::user()->designation ?? 'SLEAS / (Assignment Pending)'); ?></p>
                            <div class="mt-2 flex flex-wrap justify-center md:justify-start gap-4 text-sm text-gray-600">
                                <span><i class="fas fa-id-badge mr-1 text-gray-400"></i> ID: EMP-<?php echo e(str_pad(Auth::user()->id ?? 0, 4, '0', STR_PAD_LEFT)); ?></span>
                                <span><i class="fas fa-university mr-1 text-gray-400"></i> <?php echo e(Auth::user()->workplace ?? 'MOE / SL'); ?></span>
                            </div>
                        </div>

                        <!-- Leave Balance Summary -->
                        <div class="flex-grow grid grid-cols-1 sm:grid-cols-3 gap-4 w-full">
                            <?php $__currentLoopData = [
                                ['Casual Leave', 'fa-calendar-day', 'bg-indigo-600'],
                                ['Sick Leave', 'fa-user-md', 'bg-blue-600'],
                                ['Total', 'fa-chart-pie', 'bg-teal-600']
                            ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as [$type, $icon, $color]): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php 
                                    $used = $balances[$type]['used'] ?? 0;
                                    $limit = $balances[$type]['limit'] ?? 1;
                                    $percent = min(100, ($used / $limit) * 100);
                                ?>
                                <div class="bg-gray-50 rounded-xl p-3 border border-gray-100 shadow-sm hover:shadow-md transition-all duration-200">
                                    <div class="flex justify-between items-start mb-2">
                                        <div class="p-2 rounded-lg <?php echo e(str_replace('bg-', 'bg-opacity-10 text-', $color)); ?> <?php echo e($color); ?>">
                                            <i class="fas <?php echo e($icon); ?>"></i>
                                        </div>
                                        <span class="text-xs font-bold text-gray-400 uppercase tracking-tighter"><?php echo e($type === 'Total' ? 'ANNUAL OVERALL' : $type); ?></span>
                                    </div>
                                    <div class="flex items-end justify-between">
                                        <div>
                                            <span class="text-xl font-black text-gray-800"><?php echo e($used); ?></span>
                                            <span class="text-xs text-gray-400 font-bold">/ <?php echo e($limit); ?></span>
                                        </div>
                                        <span class="text-[10px] font-bold <?php echo e($percent > 80 ? 'text-red-500' : 'text-gray-400'); ?>"><?php echo e(round($percent)); ?>%</span>
                                    </div>
                                    <div class="w-full bg-gray-200 rounded-full h-1.5 mt-2 overflow-hidden">
                                        <div class="h-full <?php echo e($color); ?> rounded-full" style="width: <?php echo e($percent); ?>%"></div>
                                    </div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Upcoming Birthdays (Employee View) -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6 border-l-4 border-yellow-400">
                <div class="p-6 bg-white">
                    <h3 class="text-md font-bold text-gray-700 mb-4 flex items-center">
                        <i class="fas fa-birthday-cake text-yellow-500 mr-2"></i> 🎂 Upcoming Birthdays (Next 7 Days)
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                        <?php $__empty_1 = true; $__currentLoopData = $upcomingBirthdays; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ub): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <div class="flex items-center p-3 bg-yellow-50 rounded-lg border border-yellow-100 transition-transform hover:scale-[1.02] duration-200">
                                <div class="w-10 h-10 rounded-full bg-yellow-400 flex items-center justify-center text-white font-bold text-lg shadow-sm mr-3 overflow-hidden">
                                     <?php if($ub->profile_photo_path): ?>
                                        <img src="<?php echo e(asset('storage/' . $ub->profile_photo_path)); ?>" class="w-full h-full object-cover shadow-sm">
                                    <?php else: ?>
                                        <?php echo e(substr($ub->name, 0, 1)); ?>

                                    <?php endif; ?>
                                </div>
                                <div class="overflow-hidden">
                                    <div class="font-bold text-gray-800 text-sm truncate"><?php echo e($ub->full_name ?? $ub->name); ?></div>
                                    <div class="text-xs text-yellow-700 font-bold flex items-center">
                                        <i class="far fa-calendar-alt mr-1"></i> <?php echo e($ub->birthday->format('M d')); ?>

                                    </div>
                                    <div class="text-[10px] text-gray-400 truncate"><?php echo e($ub->designation ?? 'Colleague'); ?></div>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <div class="col-span-full py-4 text-center text-gray-400 italic text-sm bg-gray-50 rounded-lg border border-dashed border-gray-200">
                                <i class="fas fa-calendar-alt mr-2 opacity-50"></i> No birthdays this week.
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Navigation Tabs -->
            <div class="flex border-b border-gray-200 mb-6 bg-white rounded-t-lg px-2">
                <button @click="tab = 'leave_details'" :class="tab === 'leave_details' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-400 hover:text-gray-700 hover:border-gray-300'" class="py-4 px-6 border-b-2 font-bold text-sm transition-all duration-200 flex items-center">
                    <i class="fas fa-calendar-check mr-2"></i> My Leaves
                </button>
                <button @click="tab = 'profile'" :class="tab === 'profile' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-400 hover:text-gray-700 hover:border-gray-300'" class="py-4 px-6 border-b-2 font-bold text-sm transition-all duration-200 flex items-center">
                    <i class="fas fa-address-card mr-2"></i> My Profile
                </button>
            </div>

            <!-- Tab Content: My Profile -->
            <div x-show="tab === 'profile'" x-transition x-cloak class="bg-white shadow-sm sm:rounded-lg p-8 animate-fade-in mb-6">
                <div class="flex justify-between items-center mb-6 border-b pb-4">
                    <h3 class="text-xl font-black text-gray-800">Professional Identity</h3>
                    <div class="flex gap-2">
                        <a href="<?php echo e(route('my.pdf')); ?>" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-bold text-xs text-white uppercase tracking-widest hover:bg-red-700 active:bg-red-800 transition-all duration-150 shadow-sm">
                            <i class="fas fa-file-pdf mr-2"></i> Download My Service Record (PDF)
                        </a>
                        <a href="<?php echo e(route('profile.edit')); ?>" class="inline-flex items-center px-4 py-2 bg-indigo-100 border border-transparent rounded-md font-bold text-xs text-indigo-700 uppercase tracking-widest hover:bg-indigo-200 active:bg-indigo-300 transition-all duration-150 shadow-sm">
                            <i class="fas fa-user-edit mr-2"></i> Edit Account
                        </a>
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-y-8 gap-x-12">
                    <div>
                        <span class="block text-xs uppercase font-black text-gray-400 tracking-widest mb-1">Full Name</span>
                        <span class="text-gray-800 font-semibold"><?php echo e(Auth::user()->full_name ?? Auth::user()->name); ?></span>
                    </div>
                    <div>
                        <span class="block text-xs uppercase font-black text-gray-400 tracking-widest mb-1">NIC Number</span>
                        <span class="text-gray-800 font-semibold"><?php echo e(Auth::user()->nic_number ?? 'XXXX-XXXX-V'); ?></span>
                    </div>
                    <div>
                        <span class="block text-xs uppercase font-black text-gray-400 tracking-widest mb-1">Workplace</span>
                        <span class="text-gray-800 font-semibold"><?php echo e(Auth::user()->workplace ?? 'Ministry of Education'); ?></span>
                    </div>
                    <div>
                        <span class="block text-xs uppercase font-black text-gray-400 tracking-widest mb-1">SLEAS Rank / Designation</span>
                        <span class="text-gray-800 font-semibold"><?php echo e(Auth::user()->designation ?? 'SLEAS'); ?></span>
                    </div>
                    <div>
                        <span class="block text-xs uppercase font-black text-gray-400 tracking-widest mb-1">Appointment Date</span>
                        <span class="text-gray-800 font-semibold"><?php echo e(Auth::user()->appointment_date ?? 'Not Specified'); ?></span>
                    </div>
                    <div>
                        <span class="block text-xs uppercase font-black text-gray-400 tracking-widest mb-1">Monthly Salary</span>
                        <span class="text-gray-800 font-bold text-indigo-600">Rs. <?php echo e(number_format(Auth::user()->salary ?? 0, 2)); ?></span>
                    </div>
                </div>
            </div>

            <!-- Tab Content: My Leaves -->
            <div x-show="tab === 'leave_details'" x-transition x-cloak class="animate-fade-in">


                <!-- Recent Leave History Table -->
                <div class="bg-white shadow-sm sm:rounded-lg overflow-hidden">
                    <div class="p-6 bg-white border-b border-gray-200 flex flex-col md:flex-row justify-between items-center gap-4">
                        <h3 class="text-lg font-bold text-gray-800">Recent Leave History</h3>
                        <div class="flex gap-2">
                             <a href="<?php echo e(route('leaves.create')); ?>" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-bold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 transition duration-150 ease-in-out shadow-sm">
                                <i class="fas fa-plus mr-2"></i> Request Leave
                            </a>
                            <a href="<?php echo e(route('leaves.my-summary-pdf')); ?>" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-bold text-xs text-white uppercase tracking-widest hover:bg-red-700 active:bg-red-900 transition duration-150 ease-in-out shadow-sm">
                                <i class="fas fa-file-pdf mr-2"></i> Download My Leave Summary (PDF)
                            </a>
                        </div>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Type</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase text-center">Days</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">From - To</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Status</th>
                                    <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <?php $__empty_1 = true; $__currentLoopData = $leaves; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $leave): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr class="hover:bg-gray-50 transition-colors duration-150">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"><?php echo e($leave->leave_type); ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-center bg-gray-50 text-indigo-600"><?php echo e($leave->requested_days); ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                        <div class="flex flex-col">
                                            <span class="font-medium"><?php echo e($leave->start_date); ?></span>
                                            <span class="text-xs text-gray-400">to <?php echo e($leave->end_date); ?></span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        <?php if($leave->status === 'Pending'): ?>
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">Pending</span>
                                        <?php elseif($leave->status === 'Approved'): ?>
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Approved</span>
                                        <?php else: ?>
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Rejected</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
                                        <?php if($leave->status === 'Pending'): ?>
                                            <a href="<?php echo e(route('leaves.edit', $leave->id)); ?>" class="text-indigo-600 hover:text-indigo-900 mr-3" title="Edit"><i class="fas fa-edit"></i></a>
                                        <?php endif; ?>
                                        <?php if(\Carbon\Carbon::parse($leave->start_date)->isFuture()): ?>
                                            <form action="<?php echo e(route('leaves.cancel', $leave->id)); ?>" method="POST" class="inline" onsubmit="return confirm('මෙම නිවාඩු අයදුම්පත අවලංගු කිරීමට ඔබට විශ්වාසද?');">
                                                <?php echo csrf_field(); ?>
                                                <button type="submit" class="text-red-600 hover:text-red-900 font-bold" title="Cancel Request">Cancel</button>
                                            </form>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="5" class="px-6 py-12 text-center text-gray-500 italic">No Recent Leaves Found</td>
                                </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                    <?php if($leaves->hasPages()): ?>
                    <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
                        <?php echo e($leaves->links()); ?>

                    </div>
                    <?php endif; ?>
                </div>
            </div>

        <?php else: ?>
            <!-- ================= ADMIN DASHBOARD (CENTRAL LIST) ================= -->
            
            <div class="bg-white shadow-sm sm:rounded-lg overflow-hidden">
                <div class="p-6 bg-white border-b border-gray-200 flex justify-between items-center">
                    <h3 class="text-lg font-bold text-gray-800">All Employee Leave Requests</h3>
                </div>
                
                <!-- Filters (TDB) -->
                
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Employee</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Type</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase text-center">Days</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Dates</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Status</th>
                                <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php $__empty_1 = true; $__currentLoopData = $leaves; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $leave): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr class="hover:bg-gray-50 transition-colors duration-150">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10">
                                            <?php
                                                $photo = $leave->user->profile_photo_path ?? ($leave->user->employee->photo ?? null);
                                            ?>
                                            <?php if($photo): ?>
                                                <img class="h-10 w-10 rounded-full object-cover" src="<?php echo e(asset('storage/' . $photo)); ?>" alt="">
                                            <?php else: ?>
                                                <div class="h-10 w-10 rounded-full bg-gray-200 flex items-center justify-center text-gray-500 uppercase font-bold text-xs">
                                                    <?php echo e(substr($leave->user->name, 0, 2)); ?>

                                                </div>
                                            <?php endif; ?>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900"><?php echo e($leave->user->full_name ?? $leave->user->name); ?></div>
                                            <div class="text-xs text-gray-500"><?php echo e($leave->user->designation ?? 'N/A'); ?></div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800"><?php echo e($leave->leave_type); ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-center text-indigo-600"><?php echo e($leave->requested_days); ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                    <div class="text-xs font-medium"><?php echo e($leave->start_date); ?></div>
                                    <div class="text-xs text-gray-400">to <?php echo e($leave->end_date); ?></div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <?php if($leave->status === 'Pending'): ?>
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">Pending</span>
                                    <?php elseif($leave->status === 'Approved'): ?>
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Approved</span>
                                    <?php else: ?>
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Rejected</span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <?php if($leave->status === 'Pending'): ?>
                                        <form action="<?php echo e(route('leaves.updateStatus', $leave->id)); ?>" method="POST" class="inline">
                                            <?php echo csrf_field(); ?>
                                            <input type="hidden" name="status" value="Approved">
                                            <button type="submit" class="text-green-600 hover:text-green-900 mr-3" title="Approve"><i class="fas fa-check-circle text-lg"></i></button>
                                        </form>
                                        <form action="<?php echo e(route('leaves.updateStatus', $leave->id)); ?>" method="POST" class="inline">
                                            <?php echo csrf_field(); ?>
                                            <input type="hidden" name="status" value="Rejected">
                                            <button type="submit" class="text-red-500 hover:text-red-700 mr-3" title="Reject"><i class="fas fa-times-circle text-lg"></i></button>
                                        </form>
                                    <?php endif; ?>
                                    
                                    <form action="<?php echo e(route('leaves.destroy', $leave->id)); ?>" method="POST" class="inline" onsubmit="return confirm('මෙම වාර්තාව මකා දැමීමට ඔබට විශ්වාසද?');">
                                        <?php echo method_field('DELETE'); ?>
                                        <?php echo csrf_field(); ?>
                                        <button type="submit" class="text-gray-400 hover:text-red-600" title="Delete"><i class="fas fa-trash-alt"></i></button>
                                    </form>
                                </td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center text-gray-500 italic">No Leave Requests Found</td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                <?php if($leaves->hasPages()): ?>
                <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
                    <?php echo e($leaves->links()); ?>

                </div>
                <?php endif; ?>
            </div>
        <?php endif; ?>

    </div>
</div>

<style>
    [x-cloak] { display: none !important; }
    .animate-fade-in { animation: fadeIn 0.3s ease-in; }
    @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
</style>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\HR System\sms-final\resources\views\leaves\index.blade.php ENDPATH**/ ?>