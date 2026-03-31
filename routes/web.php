<?php

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;
use App\Models\User;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\LeaveController;
use App\Http\Controllers\HolidayController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// පද්ධතිය පිරිසිදු කිරීමට සහ සකස් කිරීමට ඇති විශේෂ Routes
Route::get('/clear-all', function() {
    Artisan::call('config:clear');
    Artisan::call('cache:clear');
    Artisan::call('view:clear');
    Artisan::call('route:clear');
    return "All cache cleared successfully!";
});

Route::get('/migrate-db', function () {
    Artisan::call('migrate --force');
    return "Database migration successful!";
});

Route::get('/create-admin', function () {
    $admin = User::where('email', 'admin@admin.com')->first();
    if (!$admin) {
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@admin.com',
            'password' => bcrypt('admin123'),
            'role' => 'admin'
        ]);
        return "Admin account created successfully!";
    }
    return "Admin account already exists!";
});

// ප්‍රධාන Dashboard (Authenticated)
Route::get('/', [EmployeeController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// ආරක්ෂිත Routes කාණ්ඩය
Route::middleware('auth')->group(function () {
    
    // Employee කළමනාකරණය
    Route::get('/download-pdf', [EmployeeController::class, 'downloadAllPDF'])->name('employee.pdf');
    Route::get('/employees/{id}/pdf', [EmployeeController::class, 'downloadPDF'])->name('employees.pdf');
    Route::get('/my-service-record/pdf', [EmployeeController::class, 'downloadMyPDF'])->name('my.pdf');
    Route::get('/export-excel', [EmployeeController::class, 'exportExcel'])->name('employee.excel');
    
    Route::get('/add-employee', function () {
        return view('create');
    });
    
    Route::post('/store-employee', [EmployeeController::class, 'store'])->name('employee.store');
    Route::get('/edit-employee/{employee}', [EmployeeController::class, 'edit'])->name('employee.edit');
    Route::post('/update-employee/{employee}', [EmployeeController::class, 'update'])->name('employee.update');
    Route::delete('/delete-employee/{employee}', [EmployeeController::class, 'destroy'])->name('employee.destroy');
    Route::post('/employees/bulk-delete', [EmployeeController::class, 'bulkDelete'])->name('employees.bulk-delete');

    // Leave (නිවාඩු) කළමනාකරණය
    Route::get('/leaves', [LeaveController::class, 'index'])->name('leaves.index');
    Route::get('/leaves/summary', [LeaveController::class, 'summary'])->name('leaves.summary');
    Route::get('/leaves/summary/pdf', [LeaveController::class, 'exportPdf'])->name('leaves.summary.pdf');
    Route::get('/leaves/summary/excel', [LeaveController::class, 'exportExcel'])->name('leaves.summary.excel');
    Route::get('/leaves/create', [LeaveController::class, 'create'])->name('leaves.create');
    Route::post('/leaves', [LeaveController::class, 'store'])->name('leaves.store');
    Route::post('/leaves/{leave}/status', [LeaveController::class, 'updateStatus'])->name('leaves.updateStatus');
    Route::post('/leaves/{leave}/cancel', [LeaveController::class, 'cancel'])->name('leaves.cancel');
    Route::get('/leaves/{leave}/edit', [LeaveController::class, 'edit'])->name('leaves.edit');
    Route::get('/leaves/{leave}/download-pdf', [LeaveController::class, 'downloadApplicationPDF'])->name('leaves.download-pdf');
    Route::get('/my-leave-summary/pdf', [LeaveController::class, 'downloadMyLeaveSummaryPDF'])->name('leaves.my-summary-pdf');
    Route::get('/admin/zonal-summary/pdf', [LeaveController::class, 'downloadZonalAnnualSummaryPDF'])->name('leaves.zonal-summary-pdf');
    Route::put('/leaves/{leave}', [LeaveController::class, 'update'])->name('leaves.update');
    Route::delete('/leaves/{leave}', [LeaveController::class, 'destroy'])->name('leaves.destroy');

    // Admin විශේෂිත සේවා
    Route::get('/admin/employee-details/{user}', [EmployeeController::class, 'getDetails'])->name('admin.employees.details');
    Route::post('/admin/import-staff', [EmployeeController::class, 'importUsers'])->name('admin.staff.import');
    Route::get('/admin/export-staff', [EmployeeController::class, 'exportUsers'])->name('admin.staff.export');
    Route::get('/admin/download-template', [EmployeeController::class, 'downloadImportTemplate'])->name('admin.staff.template');

    // Holiday (නිවාඩු දින) කළමනාකරණය
    Route::resource('holidays', HolidayController::class)->only(['index', 'store', 'destroy']);

    // Profile සංස්කරණය
    Route::get('/profile', [EmployeeController::class, 'editSelf'])->name('profile.edit');
});

require __DIR__.'/auth.php';
