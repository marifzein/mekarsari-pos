<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
  


use App\Models\Category;
use App\Models\Supplier;
use App\Models\Customer;
use App\Models\Product;
use App\Models\ProductPrice;
use App\Models\StockMovement;
use App\Models\ClientModule;
use App\Models\FooterSetting;

use Illuminate\Support\Str;

class DeveloperController extends Controller
{

    public function index()
    {
        return view(
            'developer.index'
        );
    }

    public function resetTransaksi()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        DB::table('penerimaan_barang')->truncate();
        DB::table('penerimaan_barang_items')->truncate();

        DB::table('purchase_order_items')->truncate();
        DB::table('purchase_orders')->truncate();

        DB::table('retur_barang_items')->truncate();
        DB::table('retur_barang')->truncate();

        DB::table('shifts')->truncate();
        
        DB::table('stock_adjustments')->truncate();
        DB::table('stock_adjustment_details')->truncate();
        
        DB::table('stock_movements')->truncate();
        
        DB::table('stock_opname_details')->truncate();
        DB::table('stock_opnames')->truncate();

        DB::table('transaction_details')->truncate();
        DB::table('transactions')->truncate();               

        DB::table('stock_opname_details')->truncate();
        DB::table('stock_opnames')->truncate();

        DB::table('pembatalan_penjualans')->truncate();

        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        return back()->with(
            'success',
            'Data transaksi berhasil direset.'
        );
    }

    public function resetMaster()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        
        DB::table('categories')->truncate();

        DB::table('customers')->truncate();
        
        DB::table('product_prices')->truncate();
        DB::table('products')->truncate();

        DB::table('settings')->truncate();
        
        DB::table('shifts')->truncate();
        
        DB::table('suppliers')->truncate();

        

        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        return back()->with(
            'success',
            'Master data berhasil direset.'
        );
    }
 
  

    public function seedDemo()
    {
        DB::beginTransaction();

        try {

            /*
            |--------------------------------------------------------------------------
            | CATEGORY
            |--------------------------------------------------------------------------
            */

            $categories = [

                'Minuman',
                'Makanan',
                'Snack',
                'Sembako',
                'Sabun',
                'Perawatan',
                'ATK',
                'Frozen Food',
                'Lainnya'

            ];

            foreach($categories as $category){

                Category::firstOrCreate(

                    [
                        'name'=>$category
                    ],

                    [
                        'is_active'=>1
                    ]

                );

            }

            /*
            |--------------------------------------------------------------------------
            | SUPPLIER
            |--------------------------------------------------------------------------
            */

            $suppliers = [

                'PT Indofood',
                'PT Wings',
                'PT Unilever',
                'PT Mayora',
                'PT Garuda Food',
                'PT Siantar Top',
                'PT Sosro',
                'PT ABC',
                'PT Nabati',
                'PT Orang Tua',
                'PT Nestle',
                'PT Kino',
                'PT Kalbe',
                'PT Ultrajaya',
                'PT Mondelez'

            ];

            foreach($suppliers as $i=>$supplier){

                Supplier::firstOrCreate(

                    [
                        'nama'=>$supplier
                    ],

                    [
                        'kode'=>'SUP'.str_pad($i+1,4,'0',STR_PAD_LEFT),
                        'is_active'=>1
                    ]

                );

            }

            /*
            |--------------------------------------------------------------------------
            | CUSTOMER
            |--------------------------------------------------------------------------
            */

            $customerData = [
                ['nama' => 'Ahmad Ridwan', 'alamat' => 'Jl. Pemuda No. 12, Bojonegoro'],
                ['nama' => 'Siti Aminah', 'alamat' => 'Jl. Diponegoro Gang 3, Bojonegoro'],
                ['nama' => 'Budi Santoso', 'alamat' => 'Jl. Veteran No. 45, Bojonegoro'],
                ['nama' => 'Dewi Lestari', 'alamat' => 'Perumahan Pondok Indah Blok C-4, Bojonegoro'],
                ['nama' => 'Rian Hidayat', 'alamat' => 'Jl. Panglima Sudirman No. 88, Bojonegoro'],
                ['nama' => 'Mega Utami', 'alamat' => 'Jl. Gajah Mada Gang Kelinci, Bojonegoro'],
                ['nama' => 'Eko Prasetyo', 'alamat' => 'Jl. Basuki Rahmat No. 101, Bojonegoro'],
                ['nama' => 'Diana Putri', 'alamat' => 'Jl. Untung Suropati No. 23, Bojonegoro'],
                ['nama' => 'Andi Wijaya', 'alamat' => 'Jl. Kartini No. 5, Bojonegoro'],
                ['nama' => 'Fitri Handayani', 'alamat' => 'Jl. Teuku Umar No. 14, Bojonegoro'],
                ['nama' => 'Hendra Wijaya', 'alamat' => 'Jl. Rajawali Gang Damai, Bojonegoro'],
                ['nama' => 'Anisa Rahmawati', 'alamat' => 'Perum Asri Mulia Blok A-12, Bojonegoro'],
                ['nama' => 'Rudi Hermawan', 'alamat' => 'Jl. Hayam Wuruk No. 67, Bojonegoro'],
                ['nama' => 'Sri Wahyuni', 'alamat' => 'Jl. Imam Bonjol No. 9, Bojonegoro'],
                ['nama' => 'Doni Setiawan', 'alamat' => 'Jl. Ki Hajar Dewantara No. 34, Bojonegoro'],
                ['nama' => 'Larasati Putri', 'alamat' => 'Jl. Dr. Wahidin No. 56, Bojonegoro'],
                ['nama' => 'Fajar Nugroho', 'alamat' => 'Jl. Ronggolawe Gang 2, Bojonegoro'],
                ['nama' => 'Indah Permatasari', 'alamat' => 'Jl. Mastrip No. 78, Bojonegoro'],
                ['nama' => 'Bambang Pamungkas', 'alamat' => 'Jl. Lettu Suyitno No. 112, Bojonegoro'],
                ['nama' => 'Novianti', 'alamat' => 'Jl. Patimura No. 40, Bojonegoro']
            ];

            foreach ($customerData as $i => $data) {

                Customer::firstOrCreate(
                    [
                        'nama' => $data['nama']
                    ],
                    [
                        'kode_pelanggan' => 'CUST' . str_pad($i + 1, 4, '0', STR_PAD_LEFT),
                        'telepon'        => '08' . rand(111111111, 999999999),
                        'alamat'         => $data['alamat'] // 💡 SEKARANG ALAMAT SUDAH MASUK SEEDER!
                    ]
                );

            }

            /*
            |--------------------------------------------------------------------------
            | PRODUCT
            |--------------------------------------------------------------------------
            */

            // array $products 
            // (50 produk - Diperbarui menggunakan logika potongan per pcs)
            $products = [   

                // =====================================================
                // MAKANAN
                // =====================================================

                [
                    'kode'=>'MKN0001',
                    'nama'=>'Indomie Goreng',
                    'kategori'=>'Makanan',
                    'supplier'=>'CV Sumber Rejeki',
                    'brand'=>'Indomie',
                    'harga_beli'=>2900,
                    'harga'=>3500,
                    'grosir'=>[
                        ['min_qty'=>10,'potongan'=>100],
                        ['min_qty'=>20,'potongan'=>200],
                        ['min_qty'=>50,'potongan'=>300],
                    ]
                ],

                [
                    'kode'=>'MKN0002',
                    'nama'=>'Indomie Soto',
                    'kategori'=>'Makanan',
                    'supplier'=>'CV Sumber Rejeki',
                    'brand'=>'Indomie',
                    'harga_beli'=>2900,
                    'harga'=>3500,
                    'grosir'=>[
                        ['min_qty'=>10,'potongan'=>100],
                        ['min_qty'=>20,'potongan'=>200],
                        ['min_qty'=>50,'potongan'=>300],
                    ]
                ],

                [
                    'kode'=>'MKN0003',
                    'nama'=>'Indomie Kari Ayam',
                    'kategori'=>'Makanan',
                    'supplier'=>'CV Sumber Rejeki',
                    'brand'=>'Indomie',
                    'harga_beli'=>2900,
                    'harga'=>3500,
                    'grosir'=>[
                        ['min_qty'=>10,'potongan'=>100],
                        ['min_qty'=>20,'potongan'=>200],
                        ['min_qty'=>50,'potongan'=>300],
                    ]
                ],

                [
                    'kode'=>'MKN0004',
                    'nama'=>'Mie Sedaap Goreng',
                    'kategori'=>'Makanan',
                    'supplier'=>'CV Berkah Jaya',
                    'brand'=>'Mie Sedaap',
                    'harga_beli'=>3000,
                    'harga'=>3600,
                    'grosir'=>[
                        ['min_qty'=>10,'potongan'=>100],
                        ['min_qty'=>20,'potongan'=>200],
                        ['min_qty'=>50,'potongan'=>300],
                    ]
                ],

                [
                    'kode'=>'MKN0005',
                    'nama'=>'Mie Sedaap Soto',
                    'kategori'=>'Makanan',
                    'supplier'=>'CV Berkah Jaya',
                    'brand'=>'Mie Sedaap',
                    'harga_beli'=>3000,
                    'harga'=>3600,
                    'grosir'=>[
                        ['min_qty'=>10,'potongan'=>100],
                        ['min_qty'=>20,'potongan'=>200],
                        ['min_qty'=>50,'potongan'=>300],
                    ]
                ],

                [
                    'kode'=>'MKN0006',
                    'nama'=>'Supermi Ayam Bawang',
                    'kategori'=>'Makanan',
                    'supplier'=>'PT Distributor Nusantara',
                    'brand'=>'Supermi',
                    'harga_beli'=>2850,
                    'harga'=>3400,
                    'grosir'=>[
                        ['min_qty'=>10,'potongan'=>100],
                        ['min_qty'=>20,'potongan'=>200],
                        ['min_qty'=>50,'potongan'=>300],
                    ]
                ],

                [
                    'kode'=>'MKN0007',
                    'nama'=>'Pop Mie Ayam',
                    'kategori'=>'Makanan',
                    'supplier'=>'PT Distributor Nusantara',
                    'brand'=>'Pop Mie',
                    'harga_beli'=>5000,
                    'harga'=>6000,
                    'grosir'=>[
                        ['min_qty'=>12,'potongan'=>200],
                        ['min_qty'=>24,'potongan'=>400],
                        ['min_qty'=>48,'potongan'=>600],
                    ]
                ],

                [
                    'kode'=>'MKN0008',
                    'nama'=>'Sarimi Isi 2 Ayam Kecap',
                    'kategori'=>'Makanan',
                    'supplier'=>'CV Sumber Rejeki',
                    'brand'=>'Sarimi',
                    'harga_beli'=>2800,
                    'harga'=>3400,
                    'grosir'=>[
                        ['min_qty'=>10,'potongan'=>100],
                        ['min_qty'=>20,'potongan'=>200],
                        ['min_qty'=>50,'potongan'=>300],
                    ]
                ],

                // =====================================================
                // MINUMAN
                // =====================================================

                [
                    'kode'=>'MNM0001',
                    'nama'=>'Aqua 600ml',
                    'kategori'=>'Minuman',
                    'supplier'=>'PT Tirta Distribusi',
                    'brand'=>'Aqua',
                    'harga_beli'=>2800,
                    'harga'=>3500,
                    'grosir'=>[
                        ['min_qty'=>24,'potongan'=>100],
                        ['min_qty'=>48,'potongan'=>200],
                        ['min_qty'=>96,'potongan'=>300],
                    ]
                ],

                [
                    'kode'=>'MNM0002',
                    'nama'=>'Le Minerale 600ml',
                    'kategori'=>'Minuman',
                    'supplier'=>'PT Tirta Distribusi',
                    'brand'=>'Le Minerale',
                    'harga_beli'=>2600,
                    'harga'=>3200,
                    'grosir'=>[
                        ['min_qty'=>24,'potongan'=>100],
                        ['min_qty'=>48,'potongan'=>200],
                        ['min_qty'=>96,'potongan'=>300],
                    ]
                ],

                [
                    'kode'=>'MNM0003',
                    'nama'=>'Club 600ml',
                    'kategori'=>'Minuman',
                    'supplier'=>'PT Tirta Distribusi',
                    'brand'=>'Club',
                    'harga_beli'=>2200,
                    'harga'=>2800,
                    'grosir'=>[
                        ['min_qty'=>24,'potongan'=>100],
                        ['min_qty'=>48,'potongan'=>200],
                        ['min_qty'=>96,'potongan'=>300],
                    ]
                ],

                [
                    'kode'=>'MNM0004',
                    'nama'=>'Teh Pucuk Harum 350ml',
                    'kategori'=>'Minuman',
                    'supplier'=>'PT Sinar Niaga',
                    'brand'=>'Teh Pucuk',
                    'harga_beli'=>3800,
                    'harga'=>4500,
                    'grosir'=>[
                        ['min_qty'=>12,'potongan'=>200],
                        ['min_qty'=>24,'potongan'=>300],
                        ['min_qty'=>48,'potongan'=>400],
                    ]
                ],

                [
                    'kode'=>'MNM0005',
                    'nama'=>'Teh Botol Sosro 450ml',
                    'kategori'=>'Minuman',
                    'supplier'=>'PT Sinar Niaga',
                    'brand'=>'Sosro',
                    'harga_beli'=>4300,
                    'harga'=>5200,
                    'grosir'=>[
                        ['min_qty'=>12,'potongan'=>200],
                        ['min_qty'=>24,'potongan'=>300],
                        ['min_qty'=>48,'potongan'=>400],
                    ]
                ],

                [
                    'kode'=>'MNM0006',
                    'nama'=>'Frestea Melati',
                    'kategori'=>'Minuman',
                    'supplier'=>'PT Sinar Niaga',
                    'brand'=>'Frestea',
                    'harga_beli'=>4200,
                    'harga'=>5000,
                    'grosir'=>[
                        ['min_qty'=>12,'potongan'=>200],
                        ['min_qty'=>24,'potongan'=>300],
                        ['min_qty'=>48,'potongan'=>400],
                    ]
                ],

                [
                    'kode'=>'MNM0007',
                    'nama'=>'Pocari Sweat 500ml',
                    'kategori'=>'Minuman',
                    'supplier'=>'PT Kalbe Distribusi',
                    'brand'=>'Pocari',
                    'harga_beli'=>6200,
                    'harga'=>7500,
                    'grosir'=>[
                        ['min_qty'=>12,'potongan'=>200],
                        ['min_qty'=>24,'potongan'=>400],
                        ['min_qty'=>48,'potongan'=>600],
                    ]
                ],

                [
                    'kode'=>'MNM0008',
                    'nama'=>'Mizone Lychee Lemon',
                    'kategori'=>'Minuman',
                    'supplier'=>'PT Kalbe Distribusi',
                    'brand'=>'Mizone',
                    'harga_beli'=>4800,
                    'harga'=>6000,
                    'grosir'=>[
                        ['min_qty'=>12,'potongan'=>200],
                        ['min_qty'=>24,'potongan'=>400],
                        ['min_qty'=>48,'potongan'=>600],
                    ]
                ],

                // =====================================================
                // SNACK
                // =====================================================

                [
                    'kode'=>'SNK0001',
                    'nama'=>'Chitato Sapi Panggang',
                    'kategori'=>'Snack',
                    'supplier'=>'CV Makmur Sentosa',
                    'brand'=>'Chitato',
                    'harga_beli'=>8200,
                    'harga'=>9800,
                    'grosir'=>[
                        ['min_qty'=>10,'potongan'=>300],
                        ['min_qty'=>20,'potongan'=>500],
                        ['min_qty'=>40,'potongan'=>700],
                    ]
                ],

                [
                    'kode'=>'SNK0002',
                    'nama'=>'Qtela Singkong Original',
                    'kategori'=>'Snack',
                    'supplier'=>'CV Makmur Sentosa',
                    'brand'=>'Qtela',
                    'harga_beli'=>7300,
                    'harga'=>8900,
                    'grosir'=>[
                        ['min_qty'=>10,'potongan'=>300],
                        ['min_qty'=>20,'potongan'=>500],
                        ['min_qty'=>40,'potongan'=>700],
                    ]
                ],

                [
                    'kode'=>'SNK0003',
                    'nama'=>'Piattos Cheese',
                    'kategori'=>'Snack',
                    'supplier'=>'PT Distributor Nusantara',
                    'brand'=>'Piattos',
                    'harga_beli'=>7000,
                    'harga'=>8500,
                    'grosir'=>[
                        ['min_qty'=>10,'potongan'=>200],
                        ['min_qty'=>20,'potongan'=>400],
                        ['min_qty'=>40,'potongan'=>600],
                    ]
                ],

                [
                    'kode'=>'SNK0004',
                    'nama'=>'Taro Net',
                    'kategori'=>'Snack',
                    'supplier'=>'PT Distributor Nusantara',
                    'brand'=>'Taro',
                    'harga_beli'=>6700,
                    'harga'=>8200,
                    'grosir'=>[
                        ['min_qty'=>10,'potongan'=>200],
                        ['min_qty'=>20,'potongan'=>400],
                        ['min_qty'=>40,'potongan'=>600],
                    ]
                ],

                [
                    'kode'=>'SNK0005',
                    'nama'=>'Beng Beng',
                    'kategori'=>'Snack',
                    'supplier'=>'PT Sinar Niaga',
                    'brand'=>'Beng Beng',
                    'harga_beli'=>2200,
                    'harga'=>2800,
                    'grosir'=>[
                        ['min_qty'=>24,'potongan'=>100],
                        ['min_qty'=>48,'potongan'=>200],
                        ['min_qty'=>96,'potongan'=>300],
                    ]
                ],

                [
                    'kode'=>'SNK0006',
                    'nama'=>'Oreo Vanilla',
                    'kategori'=>'Snack',
                    'supplier'=>'PT Sinar Niaga',
                    'brand'=>'Oreo',
                    'harga_beli'=>6900,
                    'harga'=>8500,
                    'grosir'=>[
                        ['min_qty'=>10,'potongan'=>200],
                        ['min_qty'=>20,'potongan'=>400],
                        ['min_qty'=>40,'potongan'=>600],
                    ]
                ],

                // =====================================================
                // SABUN & SHAMPOO
                // =====================================================

                [
                    'kode'=>'SBN0001',
                    'nama'=>'Lifebuoy Merah 80gr',
                    'kategori'=>'Sabun',
                    'supplier'=>'PT Mitra Unilever',
                    'brand'=>'Lifebuoy',
                    'harga_beli'=>3600,
                    'harga'=>4500,
                    'grosir'=>[
                        ['min_qty'=>12,'potongan'=>100],
                        ['min_qty'=>24,'potongan'=>200],
                        ['min_qty'=>48,'potongan'=>300],
                    ]
                ],

                [
                    'kode'=>'SBN0002',
                    'nama'=>'Lux Soft Touch 80gr',
                    'kategori'=>'Sabun',
                    'supplier'=>'PT Mitra Unilever',
                    'brand'=>'Lux',
                    'harga_beli'=>3400,
                    'harga'=>4300,
                    'grosir'=>[
                        ['min_qty'=>12,'potongan'=>100],
                        ['min_qty'=>24,'potongan'=>200],
                        ['min_qty'=>48,'potongan'=>300],
                    ]
                ],

                [
                    'kode'=>'SBN0003',
                    'nama'=>'Giv White 80gr',
                    'kategori'=>'Sabun',
                    'supplier'=>'CV Berkah Jaya',
                    'brand'=>'Giv',
                    'harga_beli'=>2600,
                    'harga'=>3300,
                    'grosir'=>[
                        ['min_qty'=>12,'potongan'=>100],
                        ['min_qty'=>24,'potongan'=>200],
                        ['min_qty'=>48,'potongan'=>300],
                    ]
                ],

                [
                    'kode'=>'SBN0004',
                    'nama'=>'Clear Men 170ml',
                    'kategori'=>'Perawatan',
                    'supplier'=>'PT Mitra Unilever',
                    'brand'=>'Clear',
                    'harga_beli'=>25500,
                    'harga'=>29500,
                    'grosir'=>[
                        ['min_qty'=>6,'potongan'=>500],
                        ['min_qty'=>12,'potongan'=>1000],
                        ['min_qty'=>24,'potongan'=>1500],
                    ]
                ],

                [
                    'kode'=>'SBN0005',
                    'nama'=>'Pantene Anti Lepek 170ml',
                    'kategori'=>'Perawatan',
                    'supplier'=>'PT Anugerah Abadi',
                    'brand'=>'Pantene',
                    'harga_beli'=>26500,
                    'harga'=>31000,
                    'grosir'=>[
                        ['min_qty'=>6,'potongan'=>500],
                        ['min_qty'=>12,'potongan'=>1000],
                        ['min_qty'=>24,'potongan'=>1500],
                    ]
                ],

                [
                    'kode'=>'SBN0006',
                    'nama'=>'Head & Shoulders Cool 170ml',
                    'kategori'=>'Perawatan',
                    'supplier'=>'PT Anugerah Abadi',
                    'brand'=>'Head & Shoulders',
                    'harga_beli'=>28500,
                    'harga'=>33500,
                    'grosir'=>[
                        ['min_qty'=>6,'potongan'=>500],
                        ['min_qty'=>12,'potongan'=>1000],
                        ['min_qty'=>24,'potongan'=>1500],
                    ]
                ],

                // =====================================================
                // PASTA GIGI
                // =====================================================

                [
                    'kode'=>'KSH0001',
                    'nama'=>'Pepsodent Herbal 190gr',
                    'kategori'=>'Perawatan',
                    'supplier'=>'PT Mitra Unilever',
                    'brand'=>'Pepsodent',
                    'harga_beli'=>10200,
                    'harga'=>12000,
                    'grosir'=>[
                        ['min_qty'=>6,'potongan'=>200],
                        ['min_qty'=>12,'potongan'=>400],
                        ['min_qty'=>24,'potongan'=>600],
                    ]
                ],

                [
                    'kode'=>'KSH0002',
                    'nama'=>'Close Up Red Hot 160gr',
                    'kategori'=>'Perawatan',
                    'supplier'=>'PT Mitra Unilever',
                    'brand'=>'Close Up',
                    'harga_beli'=>9600,
                    'harga'=>11500,
                    'grosir'=>[
                        ['min_qty'=>6,'potongan'=>200],
                        ['min_qty'=>12,'potongan'=>400],
                        ['min_qty'=>24,'potongan'=>600],
                    ]
                ],

                // =====================================================
                // DETERJEN
                // =====================================================

                [
                    'kode'=>'DET0001',
                    'nama'=>'Rinso Molto 800gr',
                    'kategori'=>'Sembako',
                    'supplier'=>'PT Mitra Unilever',
                    'brand'=>'Rinso',
                    'harga_beli'=>15800,
                    'harga'=>18500,
                    'grosir'=>[
                        ['min_qty'=>6,'potongan'=>300],
                        ['min_qty'=>12,'potongan'=>600],
                        ['min_qty'=>24,'potongan'=>900],
                    ]
                ],

                [
                    'kode'=>'DET0002',
                    'nama'=>'Daia Putih 900gr',
                    'kategori'=>'Sembako',
                    'supplier'=>'CV Berkah Jaya',
                    'brand'=>'Daia',
                    'harga_beli'=>14800,
                    'harga'=>17500,
                    'grosir'=>[
                        ['min_qty'=>6,'potongan'=>300],
                        ['min_qty'=>12,'potongan'=>600],
                        ['min_qty'=>24,'potongan'=>900],
                    ]
                ],

                // =====================================================
                // MINYAK GORENG
                // =====================================================

                [
                    'kode'=>'SMB0001',
                    'nama'=>'Bimoli 1 Liter',
                    'kategori'=>'Sembako',
                    'supplier'=>'PT Aneka Pangan',
                    'brand'=>'Bimoli',
                    'harga_beli'=>18800,
                    'harga'=>21000,
                    'grosir'=>[
                        ['min_qty'=>6,'potongan'=>200],
                        ['min_qty'=>12,'potongan'=>400],
                        ['min_qty'=>24,'potongan'=>600],
                    ]
                ],

                [
                    'kode'=>'SMB0002',
                    'nama'=>'Tropical 1 Liter',
                    'kategori'=>'Sembako',
                    'supplier'=>'PT Aneka Pangan',
                    'brand'=>'Tropical',
                    'harga_beli'=>18200,
                    'harga'=>20500,
                    'grosir'=>[
                        ['min_qty'=>6,'potongan'=>200],
                        ['min_qty'=>12,'potongan'=>400],
                        ['min_qty'=>24,'potongan'=>600],
                    ]
                ],

                [
                    'kode'=>'SMB0003',
                    'nama'=>'Sania 1 Liter',
                    'kategori'=>'Sembako',
                    'supplier'=>'PT Aneka Pangan',
                    'brand'=>'Sania',
                    'harga_beli'=>18000,
                    'harga'=>20200,
                    'grosir'=>[
                        ['min_qty'=>6,'potongan'=>200],
                        ['min_qty'=>12,'potongan'=>400],
                        ['min_qty'=>24,'potongan'=>600],
                    ]
                ],

                // =====================================================
                // SUSU
                // =====================================================

                [
                    'kode'=>'SUS0001',
                    'nama'=>'Ultra Milk Full Cream 250ml',
                    'kategori'=>'Minuman',
                    'supplier'=>'PT Ultrajaya',
                    'brand'=>'Ultra Milk',
                    'harga_beli'=>5200,
                    'harga'=>6500,
                    'grosir'=>[
                        ['min_qty'=>12,'potongan'=>200],
                        ['min_qty'=>24,'potongan'=>300],
                        ['min_qty'=>48,'potongan'=>400],
                    ]
                ],

                [
                    'kode'=>'SUS0002',
                    'nama'=>'Ultra Milk Coklat 250ml',
                    'kategori'=>'Minuman',
                    'supplier'=>'PT Ultrajaya',
                    'brand'=>'Ultra Milk',
                    'harga_beli'=>5200,
                    'harga'=>6500,
                    'grosir'=>[
                        ['min_qty'=>12,'potongan'=>200],
                        ['min_qty'=>24,'potongan'=>300],
                        ['min_qty'=>48,'potongan'=>400],
                    ]
                ],

                [
                    'kode'=>'SUS0003',
                    'nama'=>'Indomilk Coklat 190ml',
                    'kategori'=>'Minuman',
                    'supplier'=>'PT Distributor Nusantara',
                    'brand'=>'Indomilk',
                    'harga_beli'=>4300,
                    'harga'=>5500,
                    'grosir'=>[
                        ['min_qty'=>12,'potongan'=>200],
                        ['min_qty'=>24,'potongan'=>300],
                        ['min_qty'=>48,'potongan'=>400],
                    ]
                ],

                // =====================================================
                // KOPI
                // =====================================================

                [
                    'kode'=>'KOP0001',
                    'nama'=>'Kapal Api Special Mix',
                    'kategori'=>'Minuman',
                    'supplier'=>'PT Sinar Niaga',
                    'brand'=>'Kapal Api',
                    'harga_beli'=>1700,
                    'harga'=>2200,
                    'grosir'=>[
                        ['min_qty'=>20,'potongan'=>100],
                        ['min_qty'=>50,'potongan'=>150],
                        ['min_qty'=>100,'potongan'=>200],
                    ]
                ],

                [
                    'kode'=>'KOP0002',
                    'nama'=>'Good Day Cappuccino',
                    'kategori'=>'Minuman',
                    'supplier'=>'PT Sinar Niaga',
                    'brand'=>'Good Day',
                    'harga_beli'=>1800,
                    'harga'=>2400,
                    'grosir'=>[
                        ['min_qty'=>20,'potongan'=>100],
                        ['min_qty'=>50,'potongan'=>150],
                        ['min_qty'=>100,'potongan'=>200],
                    ]
                ],

                [
                    'kode'=>'KOP0003',
                    'nama'=>'Torabika Cappuccino',
                    'kategori'=>'Minuman',
                    'supplier'=>'PT Sinar Niaga',
                    'brand'=>'Torabika',
                    'harga_beli'=>1750,
                    'harga'=>2300,
                    'grosir'=>[
                        ['min_qty'=>20,'potongan'=>100],
                        ['min_qty'=>50,'potongan'=>150],
                        ['min_qty'=>100,'potongan'=>200],
                    ]
                ],

                // =====================================================
                // BISKUIT
                // =====================================================

                [
                    'kode'=>'BSK0001',
                    'nama'=>'Roma Kelapa',
                    'kategori'=>'Snack',
                    'supplier'=>'PT Sinar Niaga',
                    'brand'=>'Roma',
                    'harga_beli'=>6800,
                    'harga'=>8200,
                    'grosir'=>[
                        ['min_qty'=>10,'potongan'=>200],
                        ['min_qty'=>20,'potongan'=>400],
                        ['min_qty'=>40,'potongan'=>600],
                    ]
                ],

                [
                    'kode'=>'BSK0002',
                    'nama'=>'Better Sandwich',
                    'kategori'=>'Snack',
                    'supplier'=>'PT Sinar Niaga',
                    'brand'=>'Better',
                    'harga_beli'=>6200,
                    'harga'=>7600,
                    'grosir'=>[
                        ['min_qty'=>10,'potongan'=>200],
                        ['min_qty'=>20,'potongan'=>400],
                        ['min_qty'=>40,'potongan'=>600],
                    ]
                ],

                // =====================================================
                // ROTI
                // =====================================================

                [
                    'kode'=>'RTI0001',
                    'nama'=>'Sari Roti Tawar',
                    'kategori'=>'Makanan',
                    'supplier'=>'PT Distributor Nusantara',
                    'brand'=>'Sari Roti',
                    'harga_beli'=>12500,
                    'harga'=>14500,
                    'grosir'=>[
                        ['min_qty'=>5,'potongan'=>300],
                        ['min_qty'=>10,'potongan'=>500],
                        ['min_qty'=>20,'potongan'=>700],
                    ]
                ],

                [
                    'kode'=>'RTI0002',
                    'nama'=>'Sari Roti Coklat',
                    'kategori'=>'Makanan',
                    'supplier'=>'PT Distributor Nusantara',
                    'brand'=>'Sari Roti',
                    'harga_beli'=>5200,
                    'harga'=>6500,
                    'grosir'=>[
                        ['min_qty'=>10,'potongan'=>200],
                        ['min_qty'=>20,'potongan'=>300],
                        ['min_qty'=>40,'potongan'=>400],
                    ]
                ],

                // =====================================================
                // MINUMAN ENERGI
                // =====================================================

                [
                    'kode'=>'MNM0009',
                    'nama'=>'Kratingdaeng',
                    'kategori'=>'Minuman',
                    'supplier'=>'PT Sinar Niaga',
                    'brand'=>'Kratingdaeng',
                    'harga_beli'=>5200,
                    'harga'=>6500,
                    'grosir'=>[
                        ['min_qty'=>12,'potongan'=>200],
                        ['min_qty'=>24,'potongan'=>300],
                        ['min_qty'=>48,'potongan'=>400],
                    ]
                ],

                [
                    'kode'=>'MNM0010',
                    'nama'=>'Extra Joss',
                    'kategori'=>'Minuman',
                    'supplier'=>'PT Sinar Niaga',
                    'brand'=>'Extra Joss',
                    'harga_beli'=>900,
                    'harga'=>1200,
                    'grosir'=>[
                        ['min_qty'=>24,'potongan'=>50],
                        ['min_qty'=>48,'potongan'=>100],
                        ['min_qty'=>96,'potongan'=>150],
                    ]
                ],

                // =====================================================
                // FROZEN FOOD
                // =====================================================

                [
                    'kode'=>'FRZ0001',
                    'nama'=>'Fiesta Nugget 250gr',
                    'kategori'=>'Frozen Food',
                    'supplier'=>'PT Aneka Pangan',
                    'brand'=>'Fiesta',
                    'harga_beli'=>23500,
                    'harga'=>27000,
                    'grosir'=>[
                        ['min_qty'=>5,'potongan'=>400],
                        ['min_qty'=>10,'potongan'=>800],
                        ['min_qty'=>20,'potongan'=>1200],
                    ]
                ],

                [
                    'kode'=>'FRZ0002',
                    'nama'=>'So Nice Sosis 375gr',
                    'kategori'=>'Frozen Food',
                    'supplier'=>'PT Aneka Pangan',
                    'brand'=>'So Nice',
                    'harga_beli'=>14500,
                    'harga'=>17000,
                    'grosir'=>[
                        ['min_qty'=>5,'potongan'=>200],
                        ['min_qty'=>10,'potongan'=>400],
                        ['min_qty'=>20,'potongan'=>600],
                    ]
                ],

            ];
            foreach($products as $item){

                $category =
                    Category::where(
                        'name',
                        $item['kategori']
                    )->first();

                $supplier =
                    Supplier::where(
                        'nama',
                        $item['supplier']
                    )->first();

                $stok =
                    rand(20,150);

                $barcode =
                    '899' .
                    rand(100000000,999999999);

                while(
                    Product::where('barcode',$barcode)->exists()
                ){
                    $barcode =
                        '899'.rand(100000000,999999999);
                }

                $product =
                    Product::create([

                        'kode_barang' =>
                            $item['kode'],

                        'barcode' =>
                            $barcode,

                        'nama_barang' =>
                            $item['nama'],

                        'category_id' =>
                            optional($category)->id,

                        'supplier_id' =>
                            optional($supplier)->id,

                        'brand' =>
                            $item['brand'],

                        'catatan' =>
                            null,

                        'harga' =>
                            $item['harga'],

                        'harga_beli' =>
                            $item['harga_beli'],

                        'stok' =>
                            $stok,

                        'is_active' =>
                            1

                    ]);

                /*
                ----------------------------------------
                HARGA GROSIR (Menggunakan Kolom Potongan)
                ----------------------------------------
                */

                foreach($item['grosir'] as $g){

                    ProductPrice::create([

                        'product_id' =>
                            $product->id,

                        'min_qty' =>
                            $g['min_qty'],

                        'potongan' =>
                            $g['potongan']

                    ]);

                }

                /*
                ----------------------------------------
                STOCK MOVEMENT
                ----------------------------------------
                */

                StockMovement::create([

                    'product_id' =>
                        $product->id,

                    'type' =>
                        'Opening',

                    'qty' =>
                        $stok,

                    'stock_before' =>
                        0,

                    'stock_after' =>
                        $stok,

                    'reference_no' =>
                        'OPENING',

                    'notes' =>
                        'Seed Demo'

                ]);

            }

            DB::commit();

            return back()->with(

                'success',

                'Seed Demo berhasil dibuat.'

            );

        }

        catch(\Exception $e){

            DB::rollBack();

            return back()->with(

                'error',

                $e->getMessage()

            );

        }

    }

    public function modulesIndex()  
    {
        // Mengambil semua modul diurutkan berdasarkan nama controller
        $modules = ClientModule::orderBy('controller_name', 'asc')->get();
        
        return view('developer.modules', compact('modules'));
    }

    // hak akses modul client
    public function modulesUpdate(Request $request)
    {
        // 1. Ambil array 'active_modules' dari form. Jika kosong (tidak ada yang dicentang), jadikan array kosong
        $activeModuleIds = $request->input('active_modules', []);

        // 2. Set semua modul menjadi tidak aktif (is_active = 0) terlebih dahulu
        ClientModule::query()->update(['is_active' => false]);

        // 3. Jika ada checkbox yang dicentang, aktifkan modul tersebut berdasarkan id-nya
        if (!empty($activeModuleIds)) {
            ClientModule::whereIn('id', $activeModuleIds)->update(['is_active' => true]);
        }

        // 4. WAJIB HAPUS CACHE! Agar middleware CheckModuleAccess langsung mendeteksi konfigurasi terbaru
        Cache::forget('client_active_modules');

        return redirect()->route('developer.modules.index')->with('success', 'Konfigurasi akses modul client berhasil diperbarui!');
    }

    //reset footer
    public function resetFooter()
    {
        // 1. 💡 Hancurkan cache lama yang nahan data lama
        Cache::forget('global_footer_data');

        // 2. 💡 Ambil data segar langsung dari tabel database setelah di edit di MySQL
        $freshFooterData = FooterSetting::first(); // atau sesuaikan dengan cara ambil data footer Anda

        // 3. 💡 Set ulang cache baru dengan data segar  (misal disimpan selama 1 hari )
        if ($freshFooterData) {
            Cache::put('global_footer_data', $freshFooterData, now()->addDays(1));
        }

        return back()->with(
            'success',
            'Cache berhasil dibersihkan! Data footer kini telah sinkron dengan perubahan terbaru di MySQL.'
        );
    }
}