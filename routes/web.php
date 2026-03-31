<?php

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;
use App\Models\User;
use App\Models\Leave;
use App\Models\Holiday;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\LeaveController;
use App\Http\Controllers\HolidayController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// --- පද්ධතිය පිරිසිදු කිරීමට සහ සකස් කිරීමට ඇති විශේෂ Routes ---

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
    // දැනටමත් ඇත්නම් නැවත සෑදීම වැළැක්වීමට check කිරීමක් කරමු
    $admin = User::where('email', 'admin@admin.com')->first();
    if (!$admin) {
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@admin.com',
            'password' => bcrypt('admin123'), // ඔබ කලින් ඇසූ මුරපදය
            'role' => 'admin'
        ]);
        return "Admin account created successfully!";
    }
    return "Admin account already exists!";
});

// --- ප්‍රධාන පද්ධතියේ Routes ---

Route::get('/', [EmployeeController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard
