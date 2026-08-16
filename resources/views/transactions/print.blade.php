<!DOCTYPE html>
<html>

<head>

    <title>Print Struk</title>

    <style>

        body{
            font-family: monospace;
            width:58mm;
            margin:0 auto;
            font-size:11px;
        }

        .center{
            text-align:center;
        }

        hr{
            border:none;
            border-top:1px dashed #000;
        }

        .total-row{
            font-weight:bold;
            font-size:13px;
        }

        @media print{

            button{
                display:none;
            }

        }

    </style>

</head>

<body>

<div class="center">
    <h3>{{ strtoupper($shopSetting->nama_toko) }}</h3>
    <div>{{ $shopSetting->alamat }}</div>
    <div>Telp {{ $shopSetting->telepon }}</div>
</div>

<hr>

<div>

    Nota :
    {{ $transaction->no_nota }}

</div>

<div>

    Tgl :
    {{ $transaction->created_at->format('d-m-Y H:i') }}

</div>

<div>

    Pelanggan :
    {{-- {{ $transaction->pelanggan ?: 'Umum' }} --}}
    {{ $customer->nama ?? 'Umum' }}

</div>

<div>

    Kasir :
    {{ $transaction->user->name ?? 'Admin' }}

</div>

<hr>

<table width="100%">

@foreach($transaction->details as $item)

<tr>

    <td colspan="2">

        {{ $item->nama_barang }}

    </td>

</tr>

<tr>

    <td>

        {{ $item->qty }}
        x
        {{ number_format($item->harga,0,',','.') }}

    </td>

    <td align="right">

        {{ number_format($item->subtotal,0,',','.') }}

    </td>

</tr>

@endforeach

</table>

<hr>

<table width="100%">

<tr>

    <td>Subtotal</td>

    <td align="right">

        {{ number_format($transaction->subtotal,0,',','.') }}

    </td>

</tr>

<tr>

    <td>Diskon</td>

    <td align="right">{{ number_format($transaction->diskon, 0, ',', '.') }}

        

    </td>

</tr>

<tr class="total-row">

    <td>TOTAL</td>

    <td align="right">

        {{ number_format($transaction->grand_total,0,',','.') }}

    </td>

</tr>

</table>

<hr>

<table width="100%">

@if($transaction->cash > 0)

<tr>

    <td>Cash</td>

    <td align="right">

        {{ number_format($transaction->cash,0,',','.') }}

    </td>

</tr>

@endif

@if($transaction->voucher > 0)

<tr>

    <td>Voucher</td>

    <td align="right">

        {{ number_format($transaction->voucher,0,',','.') }}

    </td>

</tr>

@endif

@if($transaction->card > 0)

<tr>

    <td>Card</td>

    <td align="right">

        {{ number_format($transaction->card,0,',','.') }}

    </td>

</tr>

@endif


@if(($transaction->hutang ?? 0) > 0)
    <tr>
        <td>Kasbon</td>
        <td align="right">{{ number_format($transaction->hutang, 0, ',', '.') }}</td>
    </tr>
    @endif



<tr>

    <td><b>Kembali</b></td>

    <td align="right">

        <b>

            {{ number_format($transaction->kembalian,0,',','.') }}

        </b>

    </td>

</tr>

</table>

<hr>

<div>

    Total Item :
    {{ $transaction->details->sum('qty') }}

</div>

<hr>

{{-- <div class="center">

    Terima Kasih

    <br>

    Barang yang sudah dibeli

    <br>

    tidak dapat ditukar

</div> --}}

<div class="center">
    {!! nl2br(e($shopSetting->footer_nota)) !!}
</div>

<br>

{{-- <button onclick="window.print()">
    Cetak
</button> --}}

<button onclick="eksekusiCetak()">
    Cetak
</button>

<script>
// 1. Deklarasikan fungsinya terlebih dahulu di atas
function eksekusiCetak() {
    // Deteksi apakah pengguna menggunakan perangkat Mobile (HP/Tablet)
    var isMobile = /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);
    
    if (isMobile) {
        // --- JALUR HP / TABLET (Menggunakan RawBT) ---
        var currentUrl = window.location.href;
        var rawBtUrl = "rawbt:" + currentUrl;
        window.location.href = rawBtUrl;
    } else {
        // --- JALUR PC / LAPTOP ---
        window.print();
    }
}

// 2. Panggil fungsinya saat halaman selesai dimuat
window.onload = function() {
    eksekusiCetak();
};
</script>

</body>

</html>