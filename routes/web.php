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
use App\Http\Controllers\Admin\MediaController;
use App\Http\Controllers\Admin\BannerController;
use App\Http\Controllers\Admin\PageController;
use App\Http\Controllers\Admin\SpecialOfferController;
use App\Http\Controllers\Admin\RecommendedProductController;
use App\Http\Controllers\Admin\PopularProductController;
use App\Http\Controllers\Admin\ApiDocumentationController;

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
        Route::post('/products/draft', [ProductController::class, 'saveAsDraft'])->name('products.draft');
        Route::delete('/products/image/{image}', [ProductController::class, 'destroyImage'])->name('products.destroyImage');

        // Product Images Routes
        Route::prefix('products')
            ->name('products.')
            ->middleware(['auth'])
            ->group(function () {
                Route::post('{product}/images', [ProductController::class, 'uploadImages'])->name('images.upload');
                Route::delete('images/{image}', [ProductController::class, 'deleteImage'])->name('images.delete');
                Route::post('images/{image}/set-primary', [ProductController::class, 'setImageAsPrimary'])->name('images.setPrimary');
                Route::post('images/reorder', [ProductController::class, 'reorderImages'])->name('images.reorder');
            });

        // Media Library Routes
        Route::resource('media', MediaController::class);
        Route::post('media/bulk-destroy', [MediaController::class, 'bulkDestroy'])->name('media.bulk-destroy');
        Route::get('/media/list', [MediaController::class, 'list'])->name('admin.media.list');

        // Banner Management Routes
        Route::resource('banners', BannerController::class);

        // Pages Routes
        Route::resource('pages', PageController::class);
        Route::get('pages/{page}/duplicate', [PageController::class, 'duplicate'])->name('pages.duplicate');

        // Special Offers Routes
        Route::resource('special-offers', SpecialOfferController::class);
        Route::post('special-offers/{specialOffer}/toggle-featured', [SpecialOfferController::class, 'toggleFeatured'])
            ->name('special-offers.toggle-featured');
        Route::post('special-offers/{specialOffer}/duplicate', [SpecialOfferController::class, 'duplicate'])
            ->name('special-offers.duplicate');

        // Recommended Products Routes
        Route::resource('recommended-products', RecommendedProductController::class);
        Route::post('recommended-products/update-positions', [RecommendedProductController::class, 'updatePositions'])
            ->name('recommended-products.updatePositions');

        // Popular Products Routes
        Route::resource('popular-products', PopularProductController::class)->except(['edit', 'update']);
        Route::post('popular-products/update-positions', [PopularProductController::class, 'updatePositions'])
            ->name('popular-products.update-positions');

        // API Documentation Routes
        Route::get('api-documentation', [ApiDocumentationController::class, 'index'])->name('api-documentation.index');
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

// Add API route group if you plan to add API endpoints
Route::prefix('api/v1')
    ->name('api.v1.')
    ->middleware(['auth:api'])
    ->group(function () {
        // API routes here
    });

// Add sanctum middleware for API routes
Route::middleware(['auth:sanctum'])->group(function () {
    // Protected API routes
});

// Add rate limiting to sensitive routes
Route::middleware(['auth', 'throttle:60,1'])->group(function () {
    // Rate limited routes
});
