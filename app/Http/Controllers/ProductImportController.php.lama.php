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

                // skip jika baris kosong
                if (empty(array_filter($row))) {
                    continue;
                }

                $namaBarang  = trim($row[2] ?? '');
                $stok        = (int)($row[3] ?? 0);
                $hargaJual   = (int)($row[4] ?? 0);

                $kategoriNama = trim($row[5] ?? '');
                $supplierNama = trim($row[6] ?? '');

                $catatan = trim($row[7] ?? '');
                // $nonAktif   = trim($row[8] ?? '');
                $brand      = trim($row[9] ?? '');

                $hargaBeli = (int)($row[10] ?? 0);

                //------------------------------------------------
                // VALIDASI
                //------------------------------------------------

                if ($namaBarang == '') {

                    $gagal[] =
                        "Baris {$baris}<br>Nama barang kosong";

                    continue;
                }

                if ($stok < 0) {

                    $gagal[] =
                        "Baris {$baris}<br>Stock tidak valid";

                    continue;
                }

                if ($hargaJual <= 0) {

                    $gagal[] =
                        "Baris {$baris}<br>Harga jual kosong";

                    continue;
                }

                //------------------------------------------------
                // CATEGORY
                //------------------------------------------------

                $categoryId = null;

                if ($kategoriNama != '') {

                    $category =
                        Category::firstOrCreate(

                            [
                                'name' => $kategoriNama
                            ],

                            [
                                'is_active' => 1
                            ]

                        );

                    $categoryId = $category->id;
                }

                //------------------------------------------------
                // SUPPLIER
                //------------------------------------------------

                $supplierId = null;

                if ($supplierNama != '') {

                    $supplier =
                        Supplier::firstOrCreate(

                            [
                                'nama' => $supplierNama
                            ],

                            [
                                'kode' =>
                                    'SUP' .
                                    str_pad(
                                        Supplier::count() + 1,
                                        4,
                                        '0',
                                        STR_PAD_LEFT
                                    ),

                                'is_active' => 1
                            ]

                        );

                    $supplierId = $supplier->id;
                }

                //------------------------------------------------
                // GENERATE KODE BARANG
                //------------------------------------------------

                $kodeBarang =
                    Product::generateKodeBarang(
                        $namaBarang
                    );

                //------------------------------------------------
                // INSERT PRODUCT
                //------------------------------------------------

                Product::create([

                    'kode_barang'  => $kodeBarang,

                    'barcode'      => null,

                    'nama_barang'  => $namaBarang,

                    'category_id'  => $categoryId,

                    'supplier_id'  => $supplierId,

                    'brand'        => $brand,

                    'catatan'   => $catatan,

                    'harga'        => $hargaJual,

                    'harga_beli'   => $hargaBeli,

                    'stok'         => $stok
                    // ,

                    // 'is_active'    =>
                    //     strtolower($nonAktif) == 'ya'
                    //     ? 0
                    //     : 1

                ]);

                $berhasil++;
            }

            DB::commit();

        }
        catch (\Exception $e) {

            DB::rollBack();

            return back()->with(
                'error',
                $e->getMessage()
            );
        }

        dd(
            $berhasil,
            $gagal
        );
    }
}