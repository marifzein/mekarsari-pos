<!DOCTYPE html>
<html>

<head>

    <title>Print Struk</title>
    
    <style>
        /* printer thermal 58mm */
        body{
            font-family: monospace;
            width:58mm;
            margin:0 auto;
            font-size:11px;
        }

        .center{
            text-align:center;
        }

        .right{
            text-align:right;
        }

        hr{
            border:none;
            border-top:1px dashed #000;
        }

        .total-row{
            font-weight:bold;
            font-size:13px;
        }

        @media print {

            button{
                display:none;
            }

        }

    </style>

</head>

<body>

<div class="center">

    <h3>TOKO ANDA</h3>

    <div>
        Jl. Contoh No.123
    </div>

    <div>
        Telp 08123456789
    </div>

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
    <td>Voucher</td>
    <td align="right">
        {{ number_format($transaction->voucher,0,',','.') }}
    </td>
</tr>

<tr>
    <td>Card</td>
    <td align="right">
        {{ number_format($transaction->card,0,',','.') }}
    </td>
</tr>

</table>

<hr>

<table width="100%">

<tr class="total-row">
    <td><b>Total</b></td>
    <td align="right">
        <b>
            {{ number_format($transaction->grand_total,0,',','.') }}
        </b>
    </td>
</tr>

<tr>
    <td>Bayar</td>
    <td align="right">
        {{ number_format($transaction->cash,0,',','.') }}
    </td>
</tr>

<tr>
    <td>Kembali</td>
    <td align="right">
        {{ number_format($transaction->kembalian,0,',','.') }}
    </td>
</tr>

</table>  

<hr>

<div class="center">

    Barang yang sudah dibeli
tidak dapat ditukar

</div>

<br>

<button onclick="window.print()">

    Cetak

</button>

<script>

window.onload = function()
{
    window.print();
};

window.onafterprint = function()
{
    window.close();
};

</script>
</body>
</html>