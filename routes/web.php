<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PosController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\StockOpnameController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\PurchaseOrderController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\BackupController;
use App\Http\Controllers\ProductImportController;
use App\Http\Controllers\DeveloperController;
use App\Http\Controllers\StockAdjustmentController;
use App\Http\Controllers\StockCardController;
use App\Http\Controllers\PenerimaanBarangController;    
use App\Http\Controllers\LaporanPenjualanKasirController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\ReturBarangController;
use App\Http\Controllers\LaporanLabaRugiController;
use App\Http\Controllers\LaporanPenjualanProdukController;
use App\Http\Controllers\LaporanPenjualanPelangganController;
use App\Http\Controllers\Laporan\StockValuationController;
use App\Http\Controllers\DailyResetStockController;
use App\Http\Controllers\PosMobileController;
use App\Http\Controllers\PembatalanController;

/*
|--------------------------------------------------------------------------
| Redirect Root
|--------------------------------------------------------------------------
*/
Route::redirect('/', '/login');

Route::middleware('auth')->group(function () {

    /*
    |--------------------------------------------------------------------------
    | 1. GRUP TRANSAKSI POS HARI-HARI (Semua Role Boleh Akses)
    |--------------------------------------------------------------------------
    */
    Route::middleware(['can:akses-pos'])->group(function () {
        // Dashboard
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('/api/products/search', [ProductController::class, 'search']);

        // POS & API Pendukung=>dijaga middleware shift
        Route::middleware(['check.shift'])->group(function () {
            Route::get('/pos', [PosController::class, 'index'])->name('pos.index');

            // 📱 Versi Mobile & Tablet Baru
            Route::get('/pos/mobile', [PosMobileController::class, 'index'])->name('pos.mobile');

            Route::post('/api/transactions', [TransactionController::class, 'store']);
        });

        
        // shift2an========================================================
        // 💡 2. RUTE KHUSUS UNTUK PROSES ISI uang MODAL AWAL kasir (DI LUAR PROTEKSI SHIFT)
        Route::get('/pos/open-shift', [App\Http\Controllers\ShiftController::class, 'showOpenForm'])->name('pos.open-shift');
        Route::post('/pos/open-shift', [App\Http\Controllers\ShiftController::class, 'storeOpenShift'])->name('pos.store-shift');

        // 💡 RUTE close sfhift:
        Route::get('/pos/close-shift', [App\Http\Controllers\ShiftController::class, 'showCloseForm'])->name('pos.close-shift');
        Route::post('/pos/close-shift', [App\Http\Controllers\ShiftController::class, 'storeCloseShift'])->name('pos.store-close');
        
        // Jalur menu Laporan Toko -> Laporan Shift
        Route::get('/laporan/shift', [\App\Http\Controllers\ShiftController::class, 'index'])->name('laporan.shift.index');
        Route::get('/laporan/shift/{id}', [\App\Http\Controllers\ShiftController::class, 'show'])->name('laporan.shift.show');

        // shift2an========================================================


        // add store  pelanggan (di modul POS)
        Route::post('/api/customers', [CustomerController::class, 'storeApi']);
        
        // Transaksi & Print
        Route::get('/transactions', [TransactionController::class, 'index']);
        Route::get('/transactions/{id}', [TransactionController::class, 'show'])->name('transactions.show');
        Route::get('/transactions/{id}/print', [TransactionController::class, 'print'])->name('transactions.print');

        // Pelanggan
        Route::resource('customers', CustomerController::class);

        

        // Kartu Stok
        Route::get('stock-cards', [StockCardController::class, 'index'])->name('stock-cards.index');
        Route::get('stock-cards/{product}', [StockCardController::class, 'show'])->name('stock-cards.show');

        // Laporan Penjualan Kasir
        Route::get('/laporan/penjualan-kasir', [LaporanPenjualanKasirController::class, 'index'])->name('laporan.penjualan-kasir');

        
        // Profile (Breeze) & Ganti Password
        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
        Route::get('/password/change', [ProfileController::class, 'changePassword'])->name('password.change');
        Route::put('/password/change', [ProfileController::class, 'updatePassword'])->name('password.password-update');

        // Cek Jam System
        Route::get('/cekjam', function () {
            return [
                'now_string' => now()->format('Y-m-d H:i:s'),
                'php'        => date('Y-m-d H:i:s'),
                'timezone'   => config('app.timezone')
            ];
        });
    });

    /*
    |--------------------------------------------------------------------------
    | 2. GRUP OPERASIONAL TINGGI (Supervisor, Admin, Owner Boleh Akses)
    |--------------------------------------------------------------------------
    */
    Route::middleware(['can:akses-spv-keatas'])->group(function () {
        
        // Produk
        Route::resource('products', ProductController::class)->except(['show', 'destroy']);

        // Laporan Penjualan per produk
        Route::get('/laporan/penjualan-produk', [LaporanPenjualanProdukController::class, 'index']);

        // Laporan Penjualan per pelanggan
        Route::get('/laporan/penjualan-pelanggan', [LaporanPenjualanPelangganController::class, 'index']);

        // Laporan nilai asset
        Route::get('/laporan/nilai-aset-stok', [StockValuationController::class, 'index'])->name('laporan.nilai-aset');
        
        // Retur Barang
        Route::get('/api/retur/search-products', [ReturBarangController::class, 'searchProducts'])->name('api.retur.search-products');
        Route::resource('retur', ReturBarangController::class)->only(['index', 'create', 'store', 'show']);
        Route::get('/retur/{id}/print', [ReturBarangController::class, 'print'])->name('retur.print');


        // Master Data (Produk, Supplier, Kategori)
        // Manajemen Akun Karyawan (User)
        Route::resource('users', UserController::class)->except(['show', 'destroy']);
        Route::post('users/{user}/reset-password', [UserController::class, 'resetPassword'])->name('users.reset-password');

        Route::resource('suppliers', SupplierController::class)->except(['show', 'destroy']);
        // Route::resource('products', ProductController::class)->except(['show', 'destroy']);
        Route::resource('categories', CategoryController::class)->except(['show', 'destroy']);

        // Import Excel Produk
        Route::prefix('products')->group(function () {
            Route::get('/import', [ProductImportController::class, 'index'])->name('products.import');
            // Route::post('/import', [ProductImportController::class, 'import'])->name('products.import');
            Route::post('/import', [ProductImportController::class, 'import'])->name('products.import.store');
        });

        // Purchase Order (PO) & Cetak PDF
        Route::resource('purchasing', PurchaseOrderController::class)->except(['destroy']);
        Route::get('purchasing/{purchasing}/print-pdf', [PurchaseOrderController::class, 'printPdf'])->name('purchasing.print-pdf');

        

        // Penerimaan Barang
        Route::get('/penerimaan-barang', [PenerimaanBarangController::class, 'index'])->name('penerimaan.index');
        Route::get('/penerimaan-barang/create', [PenerimaanBarangController::class, 'create'])->name('penerimaan.create');
        Route::post('/penerimaan-barang', [PenerimaanBarangController::class, 'store'])->name('penerimaan.store');
        Route::get('/api/penerimaan/search-products', [PenerimaanBarangController::class, 'searchProducts']);
        Route::get('/penerimaan-barang/{id}', [PenerimaanBarangController::class, 'show'])->name('penerimaan.show');
        Route::get('/penerimaan-barang/{id}/print', [PenerimaanBarangController::class, 'print'])->name('penerimaan.print');

        // Stok Opname
        Route::get('/stock-opname', [StockOpnameController::class, 'index']);
        Route::post('/stock-opname/start', [StockOpnameController::class, 'start']);
        Route::get('/stock-opname/{stockOpname}', [StockOpnameController::class, 'show']);
        Route::post('/stock-opname/{stockOpname}', [StockOpnameController::class, 'store']);
        Route::post('/stock-opname/{stockOpname}/finish', [StockOpnameController::class, 'finish']);
        Route::get('/stock-opname/{stockOpname}/print', [StockOpnameController::class, 'print'])->name('stock-opname.print');
        
        // Fitur Reset Stok Harian Resto (Langsung Posted) Stck opname harian otomatis reset 0
        Route::prefix('inventory/daily-reset')->name('daily-reset.')->group(function () {
            Route::get('/', [DailyResetStockController::class, 'index'])->name('index');
            Route::post('/', [DailyResetStockController::class, 'store'])->name('store');
        }); 


        // Penyesuaian Stok (Stock Adjustment)
        Route::resource('stock-adjustments', StockAdjustmentController::class)->except(['show', 'destroy']);
        Route::post('/stock-adjustments/{stockAdjustment}/post', [StockAdjustmentController::class, 'post'])->name('stock-adjustments.post');
        Route::get('/stock-adjustments/{stockAdjustment}/print-pdf', 
        [StockAdjustmentController::class, 'printPdf'])->name('stock-adjustments.print-pdf');

        

        
        // Backup Database
        Route::prefix('backup')->group(function () {
        Route::get('/', [BackupController::class, 'index'])->name('backup.index');
        Route::post('/create', [BackupController::class, 'backup'])->name('backup.create');
        Route::get('/download/{file}', [BackupController::class, 'download'])->name('backup.download');
        
        Route::post('/backup/skema-only', [BackupController::class, 'backupSkemaOnly'])->name('backup.skema-only');
        });

    });

    /*
    |--------------------------------------------------------------------------
    | 3. GRUP STRATEGIS & KEUANGAN (Hanya Owner & Admin IT)
    |--------------------------------------------------------------------------
    */
    Route::middleware(['can:akses-owner-admin'])->group(function () {
        
            
        // Menu Pembatalan
    Route::get('/pembatalan', [PembatalanController::class, 'index'])->name('pembatalan.index');
    Route::get('/pembatalan/{id}/confirm', [PembatalanController::class, 'create'])->name('pembatalan.create');
    Route::post('/pembatalan/{id}/process', [PembatalanController::class, 'store'])->name('pembatalan.store');
    
    // Laporan Pembatalan
    Route::get('/pembatalan/report', [PembatalanController::class, 'report'])->name('pembatalan.report');

        // Laporan Rugi Laba Kotor
        Route::get('/laporan/laba-rugi', [LaporanLabaRugiController::class, 'index'])->name('laporan.laba-rugi');
        Route::get('/laporan/laba-rugi/excel', [LaporanLabaRugiController::class, 'exportExcel'])->name('laporan.laba-rugi.excel');
        Route::get('/laporan/laba-rugi/pdf', [LaporanLabaRugiController::class, 'exportPdf'])->name('laporan.laba-rugi.pdf');

        //delete backup
        Route::delete('/delete/{file}', [BackupController::class, 'destroy'])->name('backup.destroy');


        //Akunting=>rahasia perusahaan    
        // Master COA Routes
        Route::resource('coa', \App\Http\Controllers\CoaController::class)->except(['destroy']);
        Route::patch('coa/{coa}/toggle-status', [\App\Http\Controllers\CoaController::class, 'toggleStatus'])->name('coa.toggle-status');

        
        
    });

    /*
    |--------------------------------------------------------------------------
    | 4. GRUP TEKNIS DEVELOPER (Murni Hanya Admin IT)
    |--------------------------------------------------------------------------
    */
    Route::middleware(['can:akses-developer'])->group(function () {
        // Developer Tool
        Route::prefix('developer')->name('developer.')->group(function () {
            Route::get('/', [DeveloperController::class, 'index'])->name('index');
            Route::post('/reset-transaksi', [DeveloperController::class, 'resetTransaksi'])->name('reset.transaksi');
            Route::post('/reset-master', [DeveloperController::class, 'resetMaster'])->name('reset.master');
            Route::post('/reset-footer', [DeveloperController::class, 'resetFooter'])->name('reset.footer');    
            Route::post('/seed', [DeveloperController::class, 'seedDemo'])->name('seed');

            // 💡 RUTE TERBARU UNTUK MANAGEMEN INTEGRASI AKSES MODUL CLIENT
            Route::get('/modules', [\App\Http\Controllers\DeveloperController::class, 'modulesIndex'])->name('modules.index');
            Route::post('/modules/update', [\App\Http\Controllers\DeveloperController::class, 'modulesUpdate'])->name('modules.update');
        });
        
        // Pengaturan Profil Toko
        Route::get('/system/setting', [SettingController::class, 'index'])->name('setting.index');
        Route::put('/system/setting', [SettingController::class, 'update'])->name('setting.update');    
        
    });

});

/*
|--------------------------------------------------------------------------
| Auth Routes (Breeze/Laravel Internal)
|--------------------------------------------------------------------------
*/
require __DIR__.'/auth.php';