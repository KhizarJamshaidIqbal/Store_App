<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProductController;
use Illuminate\Support\Facades\Auth;

// Authentication Routes
Auth::routes();

// Admin Routes
Route::prefix('admin')
    ->name('admin.')
    ->middleware(['auth'])
    ->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

        // Categories
        Route::prefix('categories')->group(function () {
            Route::get('/', [CategoryController::class, 'index'])->name('categories.index');
            Route::get('/create', [CategoryController::class, 'create'])->name('categories.create');
            Route::post('/', [CategoryController::class, 'store'])->name('categories.store');
            Route::get('/{category}/edit', [CategoryController::class, 'edit'])->name('categories.edit');
            Route::put('/{category}', [CategoryController::class, 'update'])->name('categories.update');
            Route::delete('/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');

            // Trashed Categories Routes
            Route::get('/trashed', [CategoryController::class, 'trashed'])->name('categories.trashed');
            Route::post('/{id}/restore', [CategoryController::class, 'restore'])->name('categories.restore');
            Route::delete('/{id}/force-delete', [CategoryController::class, 'forceDelete'])->name('categories.force-delete');
        });

        Route::resource('products', ProductController::class);
        Route::post('products/draft/{product}', [ProductController::class, 'saveAsDraft'])->name('products.draft');
    });

// Redirect root to admin dashboard
Route::get('/', function () {
    return redirect()->route('admin.dashboard');
});

// Redirect /home to admin dashboard
Route::get('/home', function () {
    return redirect()->route('admin.dashboard');
});
