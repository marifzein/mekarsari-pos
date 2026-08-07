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

/*
|--------------------------------------------------------------------------
| Redirect Root
|--------------------------------------------------------------------------
*/




/*
|--------------------------------------------------------------------------
| Semua menu POS harus login
|--------------------------------------------------------------------------
*/

Route::redirect('/', '/login');

Route::middleware('auth')->group(function () {

    // Dashboard
    Route::get(
    '/dashboard',
    [DashboardController::class,'index']
        )->name('dashboard');

    //MODUL2 ROLE ADMIN 
    Route::middleware('role:Admin')->group(function () 
    {
        /*
        |--------------------------------------------------------------------------
        | MASTER DATA
        |--------------------------------------------------------------------------
        */
        // supplier
        Route::resource(
            'suppliers', 
            SupplierController::class
        )->except([
            'show',
            'destroy'
        ]);

        // Produk
        Route::resource(
            'products',
            ProductController::class
        )->except([
            'show',
            'destroy'
        ]);

        // Stock Card:
        // Route::get(
        //     'products/{product}/stock-card',
        //     [ProductController::class, 'stockCard']
        // )->name('products.stock-card');

        /*
        |--------------------------------------------------------------------------
        | KARTU STOK (INVENTORY MODULE) real
        |--------------------------------------------------------------------------
        */
        Route::get('stock-cards', [App\Http\Controllers\StockCardController::class, 'index'])->name('stock-cards.index');
        Route::get('stock-cards/{product}', [App\Http\Controllers\StockCardController::class, 'show'])->name('stock-cards.show');

        // PO
        Route::resource(
            'purchasing',
            PurchaseOrderController::class
        )->except([
            'destroy'
        ]);
        // Tempatkan di dalam grup Route::middleware('role:Admin')->group(function () { ... })
        Route::get('purchasing/{purchasing}/print-pdf', [PurchaseOrderController::class, 'printPdf'])
            ->name('purchasing.print-pdf');
        
        // User
        Route::resource(
            'users',
            UserController::class
        )->except([
            'show',
            'destroy'
        ]);

        Route::post(
            'users/{user}/reset-password',
            [UserController::class, 'resetPassword']
        )->name('users.reset-password');

        // Customers
        Route::resource('customers', CustomerController::class);

        // kategori
        Route::resource(
            'categories',
            CategoryController::class
        )->except([
            'show',
            'destroy'
        ]);
        

    });

    /*
    |--------------------------------------------------------------------------
    | RETUR
    |--------------------------------------------------------------------------
    */
        // Jalur API internal pencarian cepat produk retur
        Route::get('/api/retur/search-products', [ReturBarangController::class, 'searchProducts'])->name('api.retur.search-products');

        // Resource Route untuk Retur Barang (Hanya mengaktifkan index, create, store, dan show)
        Route::resource('retur', ReturBarangController::class)->only(['index', 'create', 'store', 'show']);
        //print retur
        Route::get('/retur/{id}/print', [ReturBarangController::class, 'print'])->name('retur.print');

    /*
    |--------------------------------------------------------------------------
    | TRANSAKSI
    |--------------------------------------------------------------------------
    */

    // POS
    Route::get(
        '/pos',
        [PosController::class,'index']
    );

    // Search produk
    Route::get(
        '/api/products/search',
        [ProductController::class,'search']
    );

    // Simpan transaksi
    Route::post(
        '/api/transactions',
        [TransactionController::class,'store']
    );

    // Daftar transaksi
    Route::get(
        '/transactions',
        [TransactionController::class,'index']
    );

    // Detail transaksi
    Route::get(
        '/transactions/{id}',
        [TransactionController::class,'show']
    )->name('transactions.show');

    // Print transaksi
    Route::get(
        '/transactions/{id}/print',
        [TransactionController::class,'print']
    )->name('transactions.print');

    // Cek Jam
    Route::get('/cekjam', function () {

        return [
            'now_string'=>now()->format('Y-m-d H:i:s'),
            'php'=>date('Y-m-d H:i:s'),
            'timezone'=>config('app.timezone')
        ];

    });

    // setting profile toko
    Route::middleware(['auth'])->group(function () {
        // Taruh di dalam grup middleware auth kamu
        Route::get('/system/setting', [SettingController::class, 'index'])->name('setting.index');
        Route::put('/system/setting', [SettingController::class, 'update'])->name('setting.update');
    });


    /* 
    ============================================================|
    RESET DAN SEEDING BUAT TEST
    ============================================================|
    */
    

    Route::prefix('developer')
        ->name('developer.')
        ->group(function () {

            Route::get(
                '/',
                [DeveloperController::class,'index']
            )->name('index');

            Route::post(
                '/reset-transaksi',
                [DeveloperController::class,'resetTransaksi']
            )->name('reset.transaksi');

            Route::post(
                '/reset-master',
                [DeveloperController::class,'resetMaster']
            )->name('reset.master');

            Route::post(
                '/seed',
                [DeveloperController::class,'seedDemo']
            )->name('seed');

        });





    /* 
    ============================================================|  
    BACKUP DB
    ============================================================|  
    */  
    Route::prefix('backup')
    ->group(function () {

        Route::get(
            '/',
            [BackupController::class,'index']
        )->name('backup.index');

        Route::post(
            '/create',
            [BackupController::class,'backup']
        )->name('backup.create');

        Route::get(
            '/download/{file}',
            [BackupController::class,'download']
        )->name('backup.download');

        Route::delete(
            '/delete/{file}',
            [BackupController::class,'destroy']
        )->name('backup.destroy');

        Route::post(
            '/backup/skema-only', 
            [BackupController::class, 'backupSkemaOnly']
        )->name('backup.skema-only');

    });
    /*
    IMPORT PRODUK VIA FILE EXCELL
    */
    

    Route::prefix('products')
        ->group(function () {

            Route::get(
                '/import',
                [ProductImportController::class,'index']
            )->name('products.import');

            Route::post(
                '/import',
                [ProductImportController::class,'import']
            )->name('products.import.store');

        });




    /*
    |--------------------------------------------------------------------------
    | STOCK OPNAME
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/stock-opname',
        [StockOpnameController::class,'index']
    );

    Route::post(
        '/stock-opname/start',
        [StockOpnameController::class,'start']
    );

    Route::get(
        '/stock-opname/{stockOpname}',
        [StockOpnameController::class,'show']
    );

    Route::post(
        '/stock-opname/{stockOpname}',
        [StockOpnameController::class,'store']
    );

    Route::post(
        '/stock-opname/{stockOpname}/finish',
        [StockOpnameController::class,'finish']
    );

    // cetak SO
    Route::get(
        '/stock-opname/{stockOpname}/print',
        [StockOpnameController::class,'print']
    )->name('stock-opname.print');
    
    
    /*
    |--------------------------------------------------------------------------
    | Modul Penerimaan Barang
    |--------------------------------------------------------------------------
    */
    Route::get('/penerimaan-barang', [PenerimaanBarangController::class, 'index'])->name('penerimaan.index');
    Route::get('/penerimaan-barang/create', [PenerimaanBarangController::class, 'create'])->name('penerimaan.create');
    Route::post('/penerimaan-barang', [PenerimaanBarangController::class, 'store'])->name('penerimaan.store');
    Route::get('/api/penerimaan/search-products', [PenerimaanBarangController::class, 'searchProducts']);
    Route::get('/penerimaan-barang/{id}', [PenerimaanBarangController::class, 'show'])->name('penerimaan.show');
    Route::get('/penerimaan-barang/{id}/print', [PenerimaanBarangController::class, 'print'])->name('penerimaan.print');
    
    /*
    |--------------------------------------------------------------------------
    | Stock Adjustment (SA)
    |--------------------------------------------------------------------------
    */
    Route::resource('stock-adjustments', StockAdjustmentController::class)->except(['show', 'destroy']);
    
    // Route khusus untuk memproses posting/closed dokumen SA
    Route::post('/stock-adjustments/{stockAdjustment}/post', [StockAdjustmentController::class, 'post'])
        ->name('stock-adjustments.post');

    
    /*
    |--------------------------------------------------------------------------
    | Profile (Breeze)
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/profile',
        [ProfileController::class,'edit']
    )->name('profile.edit');

    Route::patch(
        '/profile',
        [ProfileController::class,'update']
    )->name('profile.update');

    // === ganti password ===
    Route::get('/password/change', [ProfileController::class, 'changePassword'])->name('password.change');
    Route::put('/password/change', [ProfileController::class, 'updatePassword'])->name('password.password-update');

    Route::delete(
        '/profile',
        [ProfileController::class,'destroy']
    )->name('profile.destroy');

});

/*
|--------------------------------------------------------------------------
| LAPORAN
|--------------------------------------------------------------------------
*/
// laporan penjualan kasir
Route::get('/laporan/penjualan-kasir', [LaporanPenjualanKasirController::class, 'index'])
    ->name('laporan.penjualan-kasir');


// laporan rugi laba kotor
Route::get('/laporan/laba-rugi', [LaporanLabaRugiController::class, 'index'])
    ->name('laporan.laba-rugi');
Route::get('/laporan/laba-rugi/excel', [LaporanLabaRugiController::class, 'exportExcel'])->name('laporan.laba-rugi.excel');
Route::get('/laporan/laba-rugi/pdf', [LaporanLabaRugiController::class, 'exportPdf'])->name('laporan.laba-rugi.pdf');

/*
|--------------------------------------------------------------------------
| Login Register Logout
|--------------------------------------------------------------------------
*/

require __DIR__.'/auth.php';