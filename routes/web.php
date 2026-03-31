<?php

use App\Models\Leave;
use App\Models\Holiday;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\LeaveController;
use App\Http\Controllers\HolidayController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Main Dashboard (Authenticated)
Route::get('/', [EmployeeController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// Protected Routes Group
Route::middleware('auth')->group(function () {
    // Employee Resource Routes
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

    // Leave Management Routes
    Route::get('/leaves', [App\Http\Controllers\LeaveController::class, 'index'])->name('leaves.index');
    Route::get('/leaves/summary', [App\Http\Controllers\LeaveController::class, 'summary'])->name('leaves.summary');
    Route::get('/leaves/summary/pdf', [App\Http\Controllers\LeaveController::class, 'exportPdf'])->name('leaves.summary.pdf');
    Route::get('/leaves/summary/excel', [App\Http\Controllers\LeaveController::class, 'exportExcel'])->name('leaves.summary.excel');
    
    Route::get('/leaves/create', [App\Http\Controllers\LeaveController::class, 'create'])->name('leaves.create');
    Route::post('/leaves', [App\Http\Controllers\LeaveController::class, 'store'])->name('leaves.store');
    Route::post('/leaves/{leave}/status', [App\Http\Controllers\LeaveController::class, 'updateStatus'])->name('leaves.updateStatus');
    Route::post('/leaves/{leave}/cancel', [App\Http\Controllers\LeaveController::class, 'cancel'])->name('leaves.cancel');
    Route::get('/leaves/{leave}/edit', [App\Http\Controllers\LeaveController::class, 'edit'])->name('leaves.edit');
    Route::get('/leaves/{leave}/download-pdf', [App\Http\Controllers\LeaveController::class, 'downloadApplicationPDF'])->name('leaves.download-pdf');
    Route::get('/my-leave-summary/pdf', [App\Http\Controllers\LeaveController::class, 'downloadMyLeaveSummaryPDF'])->name('leaves.my-summary-pdf');
    Route::get('/admin/zonal-summary/pdf', [App\Http\Controllers\LeaveController::class, 'downloadZonalAnnualSummaryPDF'])->name('leaves.zonal-summary-pdf');
    Route::get('/admin/employee-details/{user}', [EmployeeController::class, 'getDetails'])->name('admin.employees.details');
    Route::post('/admin/import-staff', [EmployeeController::class, 'importUsers'])->name('admin.staff.import');
    Route::get('/admin/export-staff', [EmployeeController::class, 'exportUsers'])->name('admin.staff.export');
    Route::get('/admin/download-template', [EmployeeController::class, 'downloadImportTemplate'])->name('admin.staff.template');
    Route::put('/leaves/{leave}', [LeaveController::class, 'update'])->name('leaves.update');
    Route::delete('/leaves/{leave}', [LeaveController::class, 'destroy'])->name('leaves.destroy');

    // Holiday Management
    Route::resource('holidays', HolidayController::class)->only(['index', 'store', 'destroy']);

    // Self-Service Employee Profile (Replaces default Breeze profile)
    Route::get('/profile', [EmployeeController::class, 'editSelf'])->name('profile.edit');
    // Note: Profile updates route through employee.update, and user account deletion is handled by Admin
});

require __DIR__.'/auth.php';

Route::get('/migrate-db', function () {
    \Artisan::call('migrate --force');
    return "Database migration successful!";
});
