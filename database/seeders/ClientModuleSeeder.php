<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ClientModule;

class ClientModuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Daftar semua Controller dari folder app/Http/Controllers
        $controllers = [
            'BackupController',
            'CategoryController',
            'CoaController',
            'CustomerController',
            'DashboardController',
            'DeveloperController',
            'LaporanLabaRugiController',
            'LaporanPenjualanKasirController',
            'LaporanPenjualanPelangganController',
            'LaporanPenjualanProdukController',
            'PenerimaanBarangController',
            'PosController',
            'ProductController',
            'ProductImportController',
            'ProfileController',
            'PurchaseOrderController',
            'ReturBarangController',
            'SettingController',
            'ShiftController',
            'StockAdjustmentController',
            'StockCardController',
            'StockOpnameController',
            'StockValuationController',
            'SupplierController',
            'TransactionController',
            'UserController',
            'DailyResetStockController',
            'PosMobileController',
            'PembatalanController',
        ];

        // Looping untuk memasukkan atau mengupdate data ke database
        foreach ($controllers as $controller) {
            ClientModule::updateOrCreate(
                ['controller_name' => $controller], // Cari berdasarkan nama controller
                ['is_active' => true]               // Set default true (aktif)
            );
        }
    }
}