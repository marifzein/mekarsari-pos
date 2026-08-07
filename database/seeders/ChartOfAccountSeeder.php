<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ChartOfAccount; 

class ChartOfAccountSeeder extends Seeder
{
    public function run(): void
    {
        $coas = [
            // 1. ASET / HARTA (NERACA)
            ['account_code' => '10100', 'account_name' => 'Kas Tunai Laci Kasir', 'account_type' => 'HARTA', 'report_type' => 'NERACA', 'is_system' => true],
            ['account_code' => '10110', 'account_name' => 'Kas Kecil Toko / Brankas', 'account_type' => 'HARTA', 'report_type' => 'NERACA', 'is_system' => true],
            ['account_code' => '10200', 'account_name' => 'Bank BCA', 'account_type' => 'HARTA', 'report_type' => 'NERACA', 'is_system' => true],
            ['account_code' => '10210', 'account_name' => 'Bank Mandiri', 'account_type' => 'HARTA', 'report_type' => 'NERACA', 'is_system' => true],
            ['account_code' => '10220', 'account_name' => 'Bank BRI', 'account_type' => 'HARTA', 'report_type' => 'NERACA', 'is_system' => true],
            ['account_code' => '10230', 'account_name' => 'Bank BNI', 'account_type' => 'HARTA', 'report_type' => 'NERACA', 'is_system' => true],
            ['account_code' => '10240', 'account_name' => 'Bank CIMB', 'account_type' => 'HARTA', 'report_type' => 'NERACA', 'is_system' => true],
            ['account_code' => '10300', 'account_name' => 'Piutang Dagang Konsumen', 'account_type' => 'HARTA', 'report_type' => 'NERACA', 'is_system' => true],
            ['account_code' => '10350', 'account_name' => 'Piutang Klaim Retur Supplier', 'account_type' => 'HARTA', 'report_type' => 'NERACA', 'is_system' => true],
            ['account_code' => '10400', 'account_name' => 'Persediaan Barang Dagang', 'account_type' => 'HARTA', 'report_type' => 'NERACA', 'is_system' => true],
            ['account_code' => '10410', 'account_name' => 'Persediaan Barang Titipan / Konsinyasi', 'account_type' => 'HARTA', 'report_type' => 'NERACA', 'is_system' => true],
            ['account_code' => '10800', 'account_name' => 'Aset Tetap - Kendaraan Kantor', 'account_type' => 'HARTA', 'report_type' => 'NERACA', 'is_system' => true],
            ['account_code' => '10810', 'account_name' => 'Akumulasi Penyusutan Kendaraan', 'account_type' => 'HARTA', 'report_type' => 'NERACA', 'is_system' => true],

            // 2. LIABILITAS / UTANG (NERACA)
            ['account_code' => '20100', 'account_name' => 'Utang Dagang (Supplier)', 'account_type' => 'UTANG', 'report_type' => 'NERACA', 'is_system' => true],
            ['account_code' => '20200', 'account_name' => 'Utang Kredit Modal Bank', 'account_type' => 'UTANG', 'report_type' => 'NERACA', 'is_system' => true],
            ['account_code' => '20210', 'account_name' => 'Utang Leasing Kendaraan', 'account_type' => 'UTANG', 'report_type' => 'NERACA', 'is_system' => true],
            ['account_code' => '20300', 'account_name' => 'Utang Pajak PPN (Keluaran - Masukan)', 'account_type' => 'UTANG', 'report_type' => 'NERACA', 'is_system' => true],
            ['account_code' => '20310', 'account_name' => 'Utang PPh 21 / Potongan Pajak Karyawan', 'account_type' => 'UTANG', 'report_type' => 'NERACA', 'is_system' => true],

            // 3. EKUITAS / MODAL (NERACA)
            ['account_code' => '30100', 'account_name' => 'Modal Awal Pemilik', 'account_type' => 'MODAL', 'report_type' => 'NERACA', 'is_system' => true],
            ['account_code' => '30200', 'account_name' => 'Laba Ditahan', 'account_type' => 'MODAL', 'report_type' => 'NERACA', 'is_system' => true],
            ['account_code' => '30300', 'account_name' => 'Prive / Penarikan Pribadi', 'account_type' => 'MODAL', 'report_type' => 'NERACA', 'is_system' => true],

            // 4. REVENUE / PENDAPATAN (LABA RUGI)
            ['account_code' => '40100', 'account_name' => 'Pendapatan Penjualan Kasir POS', 'account_type' => 'PENDAPATAN', 'report_type' => 'LABA_RUGI', 'is_system' => true],
            ['account_code' => '40110', 'account_name' => 'Pendapatan Pembulatan Nilai Transaksi', 'account_type' => 'PENDAPATAN', 'report_type' => 'LABA_RUGI', 'is_system' => true],
            ['account_code' => '40200', 'account_name' => 'Pendapatan Komisi / Titip Jual', 'account_type' => 'PENDAPATAN', 'report_type' => 'LABA_RUGI', 'is_system' => true],
            ['account_code' => '40500', 'account_name' => 'Pendapatan Lain-Lain', 'account_type' => 'PENDAPATAN', 'report_type' => 'LABA_RUGI', 'is_system' => true],

            // 5. HARGA POKOK PENJUALAN / HPP (LABA RUGI)
            ['account_code' => '50100', 'account_name' => 'Harga Pokok Penjualan (HPP)', 'account_type' => 'HPP', 'report_type' => 'LABA_RUGI', 'is_system' => true],
            ['account_code' => '50200', 'account_name' => 'Beban Angkut Pembelian Barang', 'account_type' => 'HPP', 'report_type' => 'LABA_RUGI', 'is_system' => true],

            // 6. EXPENSE / BEBAN OPERASIONAL (LABA RUGI)
            ['account_code' => '60100', 'account_name' => 'Beban Gaji Karyawan / Kasir', 'account_type' => 'BEBAN', 'report_type' => 'LABA_RUGI', 'is_system' => true],
            ['account_code' => '60200', 'account_name' => 'Beban Listrik, Air & Internet Toko', 'account_type' => 'BEBAN', 'report_type' => 'LABA_RUGI', 'is_system' => true],
            ['account_code' => '60300', 'account_name' => 'Beban Perlengkapan Toko', 'account_type' => 'BEBAN', 'report_type' => 'LABA_RUGI', 'is_system' => true],
            ['account_code' => '60400', 'account_name' => 'Beban Barang Rusak & Kadaluarsa', 'account_type' => 'BEBAN', 'report_type' => 'LABA_RUGI', 'is_system' => true],
            ['account_code' => '60500', 'account_name' => 'Beban Kehilangan / Selisih Persediaan', 'account_type' => 'BEBAN', 'report_type' => 'LABA_RUGI', 'is_system' => true],
            ['account_code' => '60600', 'account_name' => 'Beban Bunga Kredit & Leasing', 'account_type' => 'BEBAN', 'report_type' => 'LABA_RUGI', 'is_system' => true],
            ['account_code' => '60700', 'account_name' => 'Beban PBB, Retribusi & Perizinan Toko', 'account_type' => 'BEBAN', 'report_type' => 'LABA_RUGI', 'is_system' => true],
            ['account_code' => '60710', 'account_name' => 'Beban Pajak Penghasilan (PPh Toko)', 'account_type' => 'BEBAN', 'report_type' => 'LABA_RUGI', 'is_system' => true],
            ['account_code' => '60900', 'account_name' => 'Beban Operasional Lain-Lain', 'account_type' => 'BEBAN', 'report_type' => 'LABA_RUGI', 'is_system' => true],
        ];

        foreach ($coas as $coa) {
            ChartOfAccount::updateOrCreate(
                ['account_code' => $coa['account_code']], 
                [
                    'account_name' => $coa['account_name'],
                    'account_type' => $coa['account_type'],
                    'report_type'  => $coa['report_type'],
                    'is_active'    => 1,
                    'is_system'    => $coa['is_system']
                ]
            );
        }
    }
}