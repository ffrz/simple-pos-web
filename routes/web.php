<?php

use App\Http\Controllers\ProductCategoryController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserV2Controller;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return inertia('Welcome', []);
})->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', function () {
        return inertia('Dashboard');
    })->name('dashboard');
    Route::get('/about', function () {
        return inertia('About');
    })->name('about');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/
    ', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('user', function () {
        return inertia('UserList');
    });
    Route::resource('users', UserController::class);

    Route::prefix('user-v2')->group(function() {
        Route::get('', [UserV2Controller::class, 'index']);
        Route::get('/data', [UserV2Controller::class, 'data']);
        Route::get('/add', [UserV2Controller::class, 'editor']);
        Route::get('/edit/{id}', [UserV2Controller::class, 'editor']);
        Route::post('/save', [UserV2Controller::class, 'save']);
        Route::post('/delete/{id}', [UserV2Controller::class, 'delete']);
    });

    Route::get('/inventory/product-category', [ProductCategoryController::class, 'index']);
    Route::get('/inventory/product-category/data', [ProductCategoryController::class, 'data']);
    Route::resource('/inventory/product-category', ProductCategoryController::class);
});

require __DIR__ . '/auth.php';
