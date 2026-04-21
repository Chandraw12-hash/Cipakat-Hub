<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\LayananSuratController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\KeuanganController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\ProdukUmkmController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProdukOrderController;
use App\Http\Controllers\PengumumanController;
use App\Models\Pengumuman;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Landing Page dengan Pengumuman
Route::get('/', function () {
    $pengumuman = Pengumuman::where('status', 'published')
        ->latest('published_at')
        ->limit(5)
        ->get();
    return view('welcome', compact('pengumuman'));
})->name('landing');

// ========== PRODUK UMKM (PUBLIK - TANPA LOGIN) ==========
Route::get('/produk', [ProdukUmkmController::class, 'index'])->name('produk.index');
Route::get('/produk/{id}', [ProdukUmkmController::class, 'show'])->name('produk.show');
Route::get('/produk/kategori/{kategori}', [ProdukUmkmController::class, 'filterByKategori'])->name('produk.kategori');

// ========== DASHBOARD & FITUR YANG BUTUH LOGIN ==========
Route::middleware(['auth', 'verified'])->group(function () {

    // Dashboard utama
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Profile routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // ========== MANAJEMEN PRODUK (BUTUH LOGIN) ==========
    Route::middleware(['auth'])->group(function () {
        Route::get('/manajemen-produk', [ProdukUmkmController::class, 'adminIndex'])->name('produk.admin');
        Route::get('/manajemen-produk/create', [ProdukUmkmController::class, 'create'])->name('produk.create');
        Route::post('/manajemen-produk', [ProdukUmkmController::class, 'store'])->name('produk.store');
        Route::get('/manajemen-produk/{id}/edit', [ProdukUmkmController::class, 'edit'])->name('produk.edit');
        Route::put('/manajemen-produk/{id}', [ProdukUmkmController::class, 'update'])->name('produk.update');
        Route::delete('/manajemen-produk/{id}', [ProdukUmkmController::class, 'destroy'])->name('produk.destroy');
    });

    // ========== ORDER PRODUK (PEMESANAN - BUTUH LOGIN) ==========
    Route::prefix('produk')->group(function () {
        Route::get('/order/{id}', [ProdukOrderController::class, 'create'])->name('produk.order');
        Route::post('/order/{id}', [ProdukOrderController::class, 'store'])->name('produk.order.store');
        Route::get('/order-history', [ProdukOrderController::class, 'history'])->name('produk.order.history');
    });

    // ========== LAYANAN SURAT ==========
    Route::prefix('layanan')->group(function () {
        Route::get('/', [LayananSuratController::class, 'index'])->name('layanan.index');
        Route::get('/create', [LayananSuratController::class, 'create'])->name('layanan.create');
        Route::post('/', [LayananSuratController::class, 'store'])->name('layanan.store');
        Route::get('/{id}', [LayananSuratController::class, 'show'])->name('layanan.show');
        Route::get('/{id}/tracking', [LayananSuratController::class, 'tracking'])->name('layanan.tracking');
    });

    Route::middleware(['role:admin,petugas'])->group(function () {
        Route::get('/admin-layanan', [LayananSuratController::class, 'adminIndex'])->name('layanan.admin');
        Route::post('/layanan/{id}/approve', [LayananSuratController::class, 'approve'])->name('layanan.approve');
        Route::post('/layanan/{id}/reject', [LayananSuratController::class, 'reject'])->name('layanan.reject');
        Route::post('/layanan/{id}/complete', [LayananSuratController::class, 'complete'])->name('layanan.complete');
    });

    Route::get('/layanan/download/{id}', [LayananSuratController::class, 'download'])->name('layanan.download');

    // ========== BOOKING ==========
    Route::prefix('booking')->group(function () {
        Route::get('/', [BookingController::class, 'index'])->name('booking.index');
        Route::get('/create', [BookingController::class, 'create'])->name('booking.create');
        Route::post('/', [BookingController::class, 'store'])->name('booking.store');
        Route::get('/{id}', [BookingController::class, 'show'])->name('booking.show');
        Route::get('/my-bookings', [BookingController::class, 'myBookings'])->name('booking.my');
        Route::get('/qris/{id}', [BookingController::class, 'showQris'])->name('booking.qris');
        Route::post('/upload-bukti/{id}', [BookingController::class, 'uploadBukti'])->name('booking.uploadBukti');
    });

    // Booking jadwal API
    Route::get('/booking/jadwal', [BookingController::class, 'getJadwal'])->name('booking.jadwal');

    // Manajemen Booking untuk Admin & Petugas
    Route::middleware(['role:admin,petugas'])->group(function () {
        Route::get('/admin-booking', [BookingController::class, 'adminIndex'])->name('booking.admin');
        Route::post('/booking/{id}/confirm', [BookingController::class, 'confirm'])->name('booking.confirm');
        Route::post('/booking/{id}/cancel', [BookingController::class, 'cancel'])->name('booking.cancel');
        Route::post('/booking/{id}/complete', [BookingController::class, 'complete'])->name('booking.complete');
    });

    // ========== KEUANGAN ==========
    Route::middleware(['role:admin,petugas'])->group(function () {
        Route::resource('keuangan', KeuanganController::class);
    });

    // ========== KHUSUS ADMIN ==========
    Route::middleware(['role:admin'])->group(function () {
        Route::resource('users', UserController::class);

        Route::prefix('laporan')->group(function () {
            Route::get('/', [LaporanController::class, 'index'])->name('laporan.index');
            Route::get('/keuangan', [LaporanController::class, 'keuangan'])->name('laporan.keuangan');
            Route::get('/surat', [LaporanController::class, 'surat'])->name('laporan.surat');
            Route::get('/booking', [LaporanController::class, 'booking'])->name('laporan.booking');
            Route::get('/export/keuangan', [LaporanController::class, 'exportKeuanganPdf'])->name('laporan.export.keuangan');
            Route::get('/export/surat', [LaporanController::class, 'exportSuratPdf'])->name('laporan.export.surat');
            Route::get('/export/booking', [LaporanController::class, 'exportBookingPdf'])->name('laporan.export.booking');
        });

        Route::get('/settings', function () {
            return view('admin.settings');
        })->name('settings');
    });
});

// ========== PENGUMUMAN DESA (PUBLIK) ==========
Route::get('/pengumuman', [PengumumanController::class, 'index'])->name('pengumuman.index');
Route::get('/pengumuman/{id}', [PengumumanController::class, 'show'])->name('pengumuman.show');

// ========== PENGUMUMAN DESA (ADMIN & PETUGAS) ==========
Route::middleware(['role:admin,petugas'])->group(function () {
    Route::get('/admin/pengumuman', [PengumumanController::class, 'adminIndex'])->name('pengumuman.admin');
    Route::get('/admin/pengumuman/create', [PengumumanController::class, 'create'])->name('pengumuman.create');
    Route::post('/admin/pengumuman', [PengumumanController::class, 'store'])->name('pengumuman.store');
    Route::get('/admin/pengumuman/{id}/edit', [PengumumanController::class, 'edit'])->name('pengumuman.edit');
    Route::put('/admin/pengumuman/{id}', [PengumumanController::class, 'update'])->name('pengumuman.update');
    Route::delete('/admin/pengumuman/{id}', [PengumumanController::class, 'destroy'])->name('pengumuman.destroy');
});


// Setting Web (khusus admin)
Route::middleware(['role:admin'])->group(function () {
    Route::get('/settings', [App\Http\Controllers\Admin\SettingController::class, 'index'])->name('settings');
    Route::put('/settings', [App\Http\Controllers\Admin\SettingController::class, 'update'])->name('settings.update');
});

// ROUTE TEST
Route::middleware(['auth'])->group(function () {
    Route::get('/test-produk', function () {
        return 'Route test berhasil!';
    });
});

require __DIR__ . '/auth.php';
