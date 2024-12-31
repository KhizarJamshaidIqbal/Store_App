<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProductController;
use App\Models\ProductImage;
use App\Models\Product;
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

        Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');
        Route::post('/products', [ProductController::class, 'store'])->name('products.store');
        Route::resource('products', ProductController::class);
        Route::post('products/draft/{product}', [ProductController::class, 'saveAsDraft'])->name('products.draft');

        // Product Image Management Routes
        Route::post('/products/{product}/images', [ProductController::class, 'uploadImages'])->name('products.images.upload');
        Route::post('/products/images/{image}/set-primary', [ProductController::class, 'setImageAsPrimary'])->name('products.images.set-primary');
        Route::post('/products/images/reorder', [ProductController::class, 'updateImageOrder'])->name('products.images.reorder');
        Route::delete('/products/images/{image}', [ProductController::class, 'deleteImage'])->name('products.images.delete');
    });

// Redirect root to admin dashboard
Route::get('/', function () {
    return redirect()->route('admin.dashboard');
});

// Redirect /home to admin dashboard
Route::get('/home', function () {
    return redirect()->route('admin.dashboard');
});

Route::model('product', Product::class);
Route::model('image', ProductImage::class);
