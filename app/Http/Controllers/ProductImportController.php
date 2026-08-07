<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use PhpOffice\PhpSpreadsheet\IOFactory;
use App\Models\Product;
use App\Models\Category;
use App\Models\Supplier;
use App\Models\ProductPrice;
use App\Models\StockMovement;   

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProductImportController extends Controller
{   
    public function index()
    {
        return view('products.import');
    }

    public function import(Request $request)
    {
        $request->validate([
        'file' => 'required|mimes:xlsx,xls'
        ]);

        $spreadsheet = IOFactory::load(
            $request->file('file')
        );

        $rows = $spreadsheet
                    ->getActiveSheet()
                    ->toArray();

        // hapus header
        array_shift($rows);

        $berhasil = 0;

        $gagal = [];

        DB::beginTransaction();

        try {

                foreach ($rows as $index => $row)
                {
                    $baris = $index + 2;

                    // ------------------------------------
                    // Skip jika baris kosong
                    // ------------------------------------

                    if(empty(array_filter($row)))
                    {
                        continue;
                    }

                    /*
                    ============================================================
                    FORMAT EXCEL

                    0  = Kode Barang
                    1  = Barcode
                    2  = Nama <Barang></Barang>
                    3  = Stock
                    4  = Harga Jual
                    5  = Kategori
                    6  = Supplier
                    7  = Keterangan
                    8  = Non Aktif
                    9  = Brand
                    10 = Harga Beli
                    11 = Min Beli 1
                    12 = Pot Beli 1
                    13 = Min Beli 2
                    14 = Pot Beli 2
                    15 = Min Beli 3
                    16 = Pot Beli 3
                    ============================================================
                    */

                    $kodeBarang   = trim($row[0] ?? '');

                    $barcode      = trim($row[1] ?? '');

                    $namaBarang   = trim($row[2] ?? '');

                    $stok         = is_numeric($row[3] ?? null)
                                    ? (int)$row[3]
                                    : 0;

                    $hargaJual    = is_numeric($row[4] ?? null)
                                    ? (int)$row[4]
                                    : 0;

                    $kategoriNama = trim($row[5] ?? '');

                    $supplierNama = trim($row[6] ?? '');

                    $catatan      = trim($row[7] ?? '');

                    $nonAktif     = trim($row[8] ?? '');

                    $brand        = trim($row[9] ?? '');

                    $hargaBeli    = is_numeric($row[10] ?? null)
                                    ? (int)$row[10]
                                    : 0;

                    $min1 = (int)($row[11] ?? 0);
                    $pot1 = (int)($row[12] ?? 0);

                    $min2 = (int)($row[13] ?? 0);
                    $pot2 = (int)($row[14] ?? 0);

                    $min3 = (int)($row[15] ?? 0);
                    $pot3 = (int)($row[16] ?? 0);

                    // ============================================
                    // VALIDASI
                    // ============================================

                    if($kodeBarang=='')
                    {
                        $gagal[] =
                            "Baris {$baris}<br>Kode barang kosong";

                        continue;
                    }

                    if($namaBarang=='')
                    {
                        $gagal[] =
                            "Baris {$baris}<br>Nama barang kosong";

                        continue;
                    }

                    if($hargaJual<=0)
                    {
                        $gagal[] =
                            "Baris {$baris}<br>Harga jual kosong";

                        continue;
                    }

                    //------------------------------------------
                    // Cek duplicate kode barang
                    //------------------------------------------

                    if(
                        Product::where(
                            'kode_barang',
                            $kodeBarang
                        )->exists()
                    ){
                        $gagal[] =
                            "Baris {$baris}<br>Kode barang <b>{$kodeBarang}</b> sudah ada";

                        continue;
                    }

                    //------------------------------------------
                    // Cek duplicate barcode
                    //------------------------------------------

                    if(
                        $barcode!='' &&
                        Product::where(
                            'barcode',
                            $barcode
                        )->exists()
                    ){
                        $gagal[] =
                            "Baris {$baris}<br>Barcode <b>{$barcode}</b> sudah ada";

                        continue;
                    }

                    //------------------------------------------
                    // Category
                    //------------------------------------------

                    $categoryId = null;

                    if($kategoriNama!='')
                    {
                        $category =
                            Category::where(
                                'name',
                                $kategoriNama
                            )->first();

                        if($category)
                        {
                            $categoryId =
                                $category->id;
                        }
                    }

                    //------------------------------------------
                    // Supplier
                    //------------------------------------------

                    $supplierId = null;

                    if($supplierNama!='')
                    {
                        $supplier =
                            Supplier::where(
                                'nama',
                                $supplierNama
                            )->first();

                        if($supplier)
                        {
                            $supplierId =
                                $supplier->id;
                        }
                    }

                    //------------------------------------------
                    // Status
                    //------------------------------------------

                    $isActive =
                        strtolower($nonAktif)=='ya'
                        ? 0
                        : 1;

                                    //------------------------------------------------
                    // INSERT PRODUCT
                    //------------------------------------------------

                    $product = Product::create([

                        'kode_barang' => $kodeBarang,

                        'barcode' => $barcode ?: null,

                        'nama_barang' => $namaBarang,

                        'category_id' => $categoryId,

                        'supplier_id' => $supplierId,

                        'brand' => $brand ?: null,

                        'catatan' => $catatan ?: null,

                        'harga' => $hargaJual,

                        'harga_beli' => $hargaBeli,

                        'stok' => $stok,

                        'is_active' => $isActive

                    ]);

                    //------------------------------------------------
                    // STOCK MOVEMENT
                    //------------------------------------------------

                    StockMovement::create([

                        'product_id' => $product->id,

                        'type' => 'IMPORT',

                        'qty' => $stok,

                        'stock_before' => 0,

                        'stock_after' => $stok,

                        'reference_no' => null,

                        'notes' => 'Import Excel'

                    ]);

                    //------------------------------------------------
                    // HARGA GROSIR 1
                    //------------------------------------------------

                    if(
                        $min1 > 0 &&
                        $pot1 > 0
                    ){

                        ProductPrice::create([

                            'product_id' => $product->id,

                            'min_qty' => $min1,

                            'harga' => max(
                                0,
                                $hargaJual - $pot1
                            )

                        ]);

                    }

                    //------------------------------------------------
                    // HARGA GROSIR 2
                    //------------------------------------------------

                    if(
                        $min2 > 0 &&
                        $pot2 > 0
                    ){

                        ProductPrice::create([

                            'product_id' => $product->id,

                            'min_qty' => $min2,

                            'harga' => max(
                                0,
                                $hargaJual - $pot2
                            )

                        ]);

                    }

                    //------------------------------------------------
                    // HARGA GROSIR 3
                    //------------------------------------------------

                    if(
                        $min3 > 0 &&
                        $pot3 > 0
                    ){

                        ProductPrice::create([

                            'product_id' => $product->id,

                            'min_qty' => $min3,

                            'harga' => max(
                                0,
                                $hargaJual - $pot3
                            )

                        ]);

                    }

                    $berhasil++;
                    }

                    DB::commit();

                    return back()->with([

                        'success' => "{$berhasil} produk berhasil diimport",

                        'import_result' => [

                            'berhasil' => $berhasil,

                            'gagal' => $gagal

                        ]

                    ]);

            }
        catch (\Exception $e) {

            DB::rollBack();

            return back()->with(
                'error',
                $e->getMessage()
            );
        }

       
    }
}