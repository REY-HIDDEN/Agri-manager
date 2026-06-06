<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BuyerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DeliveryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Agriculture Product Management System Routes
|--------------------------------------------------------------------------
*/

// Landing page (public)
Route::get('/', function () {
    return view('landing');
})->name('landing');

// Authentication routes
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:5,1');
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->middleware('throttle:5,1');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Password reset routes (guest only)
Route::middleware('guest')->group(function () {
    Route::get('/forgot-password', [AuthController::class, 'showForgotForm'])->name('password.request');
    Route::post('/forgot-password', [AuthController::class, 'sendResetLink'])->name('password.email');
    Route::get('/reset-password/{token}', [AuthController::class, 'showResetForm'])->name('password.reset');
    Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');
});

// Protected routes - all authenticated users
Route::middleware('auth')->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Products - viewable by all, manageable by admin
    Route::get('/products', [ProductController::class, 'index'])->name('products.index');
    Route::get('/products/create', [ProductController::class, 'create'])->name('products.create')->middleware('role:admin');
    Route::post('/products', [ProductController::class, 'store'])->name('products.store')->middleware('role:admin');
    Route::get('/products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit')->middleware('role:admin');
    Route::put('/products/{product}', [ProductController::class, 'update'])->name('products.update')->middleware('role:admin');
    Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('products.destroy')->middleware('role:admin');

    // Buyers - fully accessible by all authenticated users
    Route::get('/buyers', [BuyerController::class, 'index'])->name('buyers.index');
    Route::get('/buyers/create', [BuyerController::class, 'create'])->name('buyers.create');
    Route::post('/buyers', [BuyerController::class, 'store'])->name('buyers.store');
    Route::get('/buyers/{buyer}/edit', [BuyerController::class, 'edit'])->name('buyers.edit');
    Route::put('/buyers/{buyer}', [BuyerController::class, 'update'])->name('buyers.update');
    Route::delete('/buyers/{buyer}', [BuyerController::class, 'destroy'])->name('buyers.destroy');

    // Sales - fully accessible by all authenticated users
    Route::get('/sales', [SaleController::class, 'index'])->name('sales.index');
    Route::get('/sales/create', [SaleController::class, 'create'])->name('sales.create');
    Route::post('/sales', [SaleController::class, 'store'])->name('sales.store');
    Route::get('/sales/{sale}', [SaleController::class, 'show'])->name('sales.show');
    Route::get('/sales/{sale}/edit', [SaleController::class, 'edit'])->name('sales.edit')->middleware('role:admin');
    Route::put('/sales/{sale}', [SaleController::class, 'update'])->name('sales.update')->middleware('role:admin');
    Route::delete('/sales/{sale}', [SaleController::class, 'destroy'])->name('sales.destroy')->middleware('role:admin');

    // Deliveries - viewable by all, manageable by admin
    Route::get('/deliveries', [DeliveryController::class, 'index'])->name('deliveries.index');
    Route::get('/deliveries/create', [DeliveryController::class, 'create'])->name('deliveries.create')->middleware('role:admin');
    Route::post('/deliveries', [DeliveryController::class, 'store'])->name('deliveries.store')->middleware('role:admin');
    Route::get('/deliveries/{delivery}/edit', [DeliveryController::class, 'edit'])->name('deliveries.edit')->middleware('role:admin');
    Route::put('/deliveries/{delivery}', [DeliveryController::class, 'update'])->name('deliveries.update')->middleware('role:admin');
    Route::delete('/deliveries/{delivery}', [DeliveryController::class, 'destroy'])->name('deliveries.destroy')->middleware('role:admin');

    // Reports - viewable by all
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/', [ReportController::class, 'index'])->name('index');
        Route::get('/daily-sales', [ReportController::class, 'dailySales'])->name('daily-sales');
        Route::get('/monthly-sales', [ReportController::class, 'monthlySales'])->name('monthly-sales');
        Route::get('/products', [ReportController::class, 'products'])->name('products');
        Route::get('/buyers', [ReportController::class, 'buyers'])->name('buyers');
        Route::get('/buyers/{buyer}/history', [ReportController::class, 'buyerHistory'])->name('buyer-history');
        Route::get('/profit', [ReportController::class, 'profit'])->name('profit');
    });

    // User Management - Admin only
    Route::middleware('role:admin')->group(function () {
        Route::get('/users', [UserController::class, 'index'])->name('users.index');
        Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
        Route::post('/users', [UserController::class, 'store'])->name('users.store');
        Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
        Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
        Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
    });
});
