<?php $__env->startSection('header'); ?>
    <div class="flex justify-between items-center">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            <?php echo e(__('Leave Master Summary Dashboard')); ?>

        </h2>
        <a href="<?php echo e(route('leaves.index')); ?>" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
            Back to Leave List
        </a>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        
        <!-- Filters & Export -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6 p-6">
            <form method="GET" action="<?php echo e(route('leaves.summary')); ?>" class="flex flex-wrap items-end gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Month</label>
                    <select name="month" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                        <?php for($i = 1; $i <= 12; $i++): ?>
                            <option value="<?php echo e(sprintf('%02d', $i)); ?>" <?php echo e($month == $i ? 'selected' : ''); ?>>
                                <?php echo e(date('F', mktime(0, 0, 0, $i, 1))); ?>

                            </option>
                        <?php endfor; ?>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Year</label>
                    <select name="year" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                        <?php for($i = date('Y') - 5; $i <= date('Y') + 1; $i++): ?>
                            <option value="<?php echo e($i); ?>" <?php echo e($year == $i ? 'selected' : ''); ?>><?php echo e($i); ?></option>
                        <?php endfor; ?>
                    </select>
                </div>
                <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150">
                    Filter
                </button>

                <div class="ml-auto flex gap-2">
                    <a href="<?php echo e(route('leaves.summary.excel', request()->all())); ?>" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        Export to Excel
                    </a>
                    <a href="<?php echo e(route('leaves.summary.pdf', request()->all())); ?>" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        Export to PDF
                    </a>
                </div>
            </form>
        </div>

        <!-- Summary Table -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Employee</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sick Leave (21)</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Casual Leave (24)</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Annual Leave (45)</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Duty Leave</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php $__currentLoopData = $summaryData; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"><?php echo e($data['name']); ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">EMP-<?php echo e(str_pad($data['employee_id'], 4, '0', STR_PAD_LEFT)); ?></td>
                                
                                <!-- Sick Leave -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="w-full bg-gray-200 rounded-full h-2.5 mr-2" style="min-width: 80px;">
                                            <div class="bg-red-500 h-2.5 rounded-full" style="width: <?php echo e(min(($data['sick'] / 21) * 100, 100)); ?>%"></div>
                                        </div>
                                        <span class="text-xs font-semibold"><?php echo e($data['sick']); ?>/21</span>
                                    </div>
                                </td>

                                <!-- Casual Leave -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="w-full bg-gray-200 rounded-full h-2.5 mr-2" style="min-width: 80px;">
                                            <div class="bg-yellow-400 h-2.5 rounded-full" style="width: <?php echo e(min(($data['casual'] / 24) * 100, 100)); ?>%"></div>
                                        </div>
                                        <span class="text-xs font-semibold"><?php echo e($data['casual']); ?>/24</span>
                                    </div>
                                </td>

                                <!-- Annual Leave -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="w-full bg-gray-200 rounded-full h-2.5 mr-2" style="min-width: 80px;">
                                            <div class="bg-green-500 h-2.5 rounded-full" style="width: <?php echo e(min(($data['annual'] / 45) * 100, 100)); ?>%"></div>
                                        </div>
                                        <span class="text-xs font-semibold"><?php echo e($data['annual']); ?>/45</span>
                                    </div>
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-bold text-blue-600">
                                    <?php echo e($data['duty']); ?>

                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <a href="<?php echo e(route('leaves.index')); ?>?user_id=<?php echo e($data['user_id']); ?>&month=<?php echo e($month); ?>&year=<?php echo e($year); ?>" class="text-indigo-600 hover:text-indigo-900 mr-3">View Details</a>
                                </td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                    <?php if($summaryData->isEmpty()): ?>
                        <div class="text-center py-4 text-gray-500">No leave records found for this period.</div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\HR System\sms-final\resources\views\leaves\summary.blade.php ENDPATH**/ ?>