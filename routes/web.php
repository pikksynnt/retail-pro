<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    ProductController, 
    CategoryController, 
    PosController, 
    DashboardController, 
    AuthController, 
    LandingPageController, 
    MemberController,
    VendorOrderController,
    CartController,
    ReviewController,
    WishlistController // IMPORT WAJIB: Biar Wishlist nggak error
};

/**
 * IMPORT CONTROLLER KHUSUS ADMIN
 */
use App\Http\Controllers\Admin\AdminVendorController;
use App\Http\Controllers\Admin\AdminReportController;

/*
|--------------------------------------------------------------------------
| Web Routes - Marketplace UMKM Binaan (Retail Pro)
|--------------------------------------------------------------------------
*/

// =========================================================================
// 1. PUBLIC AREA (Landing Page & Katalog Publik)
// =========================================================================
Route::get('/debug-check', function() {
    return 'DASHBOARD DEBUG OK - THIS IS THE RIGHT PROJECT';
});

Route::controller(LandingPageController::class)->group(function () {
    Route::get('/', 'index')->name('landing');
    Route::get('/product/{id}', 'showProduct')->name('product.detail');
});

// =========================================================================
// 2. AUTHENTICATION
// =========================================================================
Route::controller(AuthController::class)->group(function () {
    Route::get('/login', 'showLogin')->name('login')->middleware('guest');
    Route::post('/login', 'login')->name('login.process');
    Route::get('/register', 'showRegister')->name('register')->middleware('guest');
    Route::get('/register-customer', 'showRegisterCustomer')->name('register.customer')->middleware('guest');
    Route::post('/register-customer', 'registerCustomer')->name('register.customer.process');
    Route::post('/register', 'register')->name('register.process');
    Route::post('/logout', 'logout')->name('logout');
});

// =========================================================================
// 3. PROTECTED AREA (Hanya User yang Sudah Login)
// =========================================================================
Route::middleware(['auth'])->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    /**
     * FITUR WISHLIST (Poin No. 7)
     */
    Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist.index');
    Route::post('/wishlist/add/{productId}', [WishlistController::class, 'store'])->name('wishlist.add');

    /**
     * FITUR CUSTOMER (Keranjang & Order)
     */
    Route::prefix('cart')->name('cart.')->group(function () {
        Route::get('/', [CartController::class, 'index'])->name('index');
        Route::post('/add', [CartController::class, 'store'])->name('add');
        Route::patch('/update/{id}', [CartController::class, 'update'])->name('update');
        Route::delete('/remove/{id}', [CartController::class, 'destroy'])->name('destroy');
        Route::post('/checkout', [CartController::class, 'checkout'])->name('checkout');
    });

    Route::get('/my-orders', [CartController::class, 'myOrders'])->name('customer.orders');
    Route::post('/review/store', [ReviewController::class, 'store'])->name('review.store');

    /**
     * KHUSUS ROLE: VENDOR
     */
    Route::middleware(['role:vendor'])->group(function () {
        Route::resource('categories', CategoryController::class);
        Route::resource('products', ProductController::class);

        Route::prefix('vendor/orders')->name('vendor.orders.')->group(function () {
            Route::get('/', [VendorOrderController::class, 'index'])->name('index');
            Route::get('/{id}', [VendorOrderController::class, 'show'])->name('show');
            Route::patch('/{id}/status', [VendorOrderController::class, 'updateStatus'])->name('updateStatus');
        });

        Route::resource('members', MemberController::class)->except(['show']);

        Route::prefix('pos')->name('pos.')->group(function () {
            Route::get('/', [PosController::class, 'index'])->name('index');
            Route::get('/find-product/{barcode}', [PosController::class, 'findProduct'])->name('findProduct');
            Route::get('/find-member/{code}', [PosController::class, 'findMember'])->name('findMember');
            Route::post('/store', [PosController::class, 'store'])->name('store');
            Route::get('/print/{id}', [PosController::class, 'printReceipt'])->name('print');
            Route::get('/report', [PosController::class, 'report'])->name('report');
        });
    });

    /**
     * KHUSUS ROLE: ADMIN
     */
    Route::middleware(['role:admin'])->group(function () {
        Route::prefix('admin')->name('admin.')->group(function () {
            
            // Manajemen Vendor
            Route::prefix('vendors')->name('vendors.')->group(function () {
                Route::get('/', [AdminVendorController::class, 'index'])->name('index');
                Route::get('/pending', [AdminVendorController::class, 'pending'])->name('pending');
                Route::get('/{id}', [AdminVendorController::class, 'show'])->name('show');
                Route::patch('/{id}/verify', [AdminVendorController::class, 'approve'])->name('verify');
                Route::delete('/{id}/destroy', [AdminVendorController::class, 'destroy'])->name('destroy');
            });

            // Laporan Global (Poin No. 8)
            Route::get('/reports/global', [AdminReportController::class, 'globalReport'])->name('reports.global');
        });
    });
});

// =========================================================================
// TOMBOL SAKTI: MIGRATE DARI BROWSER (KHUSUS VERCEL)
// =========================================================================
Route::get('/gas-migrate', function() {
    try {
        \Illuminate\Support\Facades\Artisan::call('migrate', ['--force' => true]);
        return "<h2>Database BERHASIL di-update!</h2><p>Sekarang silakan buka halaman depan bro!</p><a href='/'>Klik di sini ke Halaman Depan</a>";
    } catch (\Exception $e) {
        return "<h2>Gagal update database!</h2><p>Pesan eror: " . $e->getMessage() . "</p>";
    }
});