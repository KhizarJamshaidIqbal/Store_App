<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\PasswordResetController;
use App\Models\ProductImage;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

// Authentication Routes
Auth::routes();

// Admin Routes
Route::prefix('admin')
    ->name('admin.')
    ->middleware(['auth'])
    ->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::put('/profile/update', [ProfileController::class, 'update'])->name('profile.update');

        // Categories Routes
        Route::get('/categories/trashed', [CategoryController::class, 'trashed'])->name('categories.trashed');
        Route::post('/categories/{id}/restore', [CategoryController::class, 'restore'])->name('categories.restore');
        Route::delete('/categories/{id}/force-delete', [CategoryController::class, 'forceDelete'])->name('categories.force-delete');
        Route::resource('categories', CategoryController::class);

        // Products Routes

        Route::resource('products', ProductController::class);
        Route::post('products/{id}/restore', [ProductController::class, 'restore'])->name('products.restore');
        Route::delete('products/{id}/force-delete', [ProductController::class, 'forceDelete'])->name('products.force-delete');
        Route::post('products/{product}/images', [ProductController::class, 'uploadImages'])->name('products.images.upload');
        Route::delete('products/images/{image}', [ProductController::class, 'deleteImage'])->name('products.images.delete');
        Route::post('products/images/{image}/set-primary', [ProductController::class, 'setImageAsPrimary'])->name('products.images.setPrimary');
        Route::post('products/images/reorder', [ProductController::class, 'reorderImages'])->name('products.images.reorder');
        Route::post('/products/draft', [ProductController::class, 'saveAsDraft'])->name('products.draft');
        Route::delete('/products/image/{image}', [ProductController::class, 'destroyImage'])->name('products.destroyImage');
    });

Route::middleware('auth')->group(function () {
    // Forgot Password Routes
    Route::post('/forgot-password', [PasswordResetController::class, 'sendResetLink'])->name('password.email');
    Route::get('/reset-password/{token}', [PasswordResetController::class, 'showReset'])->name('password.reset');
    Route::post('/reset-password', [PasswordResetController::class, 'reset'])->name('password.update');
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
