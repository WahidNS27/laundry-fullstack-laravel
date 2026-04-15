<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('dashboard');
    }
    return redirect()->route('login');
});

Route::get('/dashboard', function () {
    $level = auth()->user()->id_level ?? null;

    if ($level == 1) {
        return redirect()->route('admin.dashboard');
    }
    elseif ($level == 2) {
        return redirect()->route('operator.orders.index');
    }
    elseif ($level == 3) {
        return redirect()->route('pimpinan.dashboard');
    }

    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Admin Routes
Route::middleware(['auth', 'role:1'])->name('admin.')->prefix('admin')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\Admin\DashboardController::class , 'index'])->name('dashboard');
    Route::resource('users', \App\Http\Controllers\Admin\UserController::class)->parameters(['users' => 'user']);
    Route::resource('services', \App\Http\Controllers\Admin\TypeOfServiceController::class)->parameters(['services' => 'service']);
});

Route::middleware(['auth', 'role:1,2'])->name('admin.')->prefix('admin')->group(function () {
    Route::resource('customers', \App\Http\Controllers\Admin\CustomerController::class);
});

// Operator Routes
Route::middleware(['auth', 'role:1,2'])->name('operator.')->prefix('operator')->group(function () {
    Route::resource('orders', \App\Http\Controllers\Operator\TransOrderController::class)->except(['edit', 'update', 'destroy']);
    Route::get('/pickup/{order}', [\App\Http\Controllers\Operator\TransLaundryPickupController::class , 'create'])->name('pickup.create');
    Route::post('/pickup/{order}', [\App\Http\Controllers\Operator\TransLaundryPickupController::class , 'store'])->name('pickup.store');
});

// Pimpinan Routes
Route::middleware(['auth', 'role:3'])->name('pimpinan.')->prefix('pimpinan')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\Pimpinan\DashboardController::class , 'index'])->name('dashboard');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class , 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class , 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class , 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
