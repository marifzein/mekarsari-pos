<!DOCTYPE html>
<html>

<head>

<meta charset="UTF-8">

<title>

Laporan Stock Opname

</title>

<style>

body{

font-family:Arial;

font-size:13px;

margin:30px;

}

table{

width:100%;

border-collapse:collapse;

margin-top:20px;

}

th,td{

border:1px solid #000;

padding:6px;

}

th{

background:#eee;

}

h2{

margin-bottom:0;

}

small{

color:#666;

}

.right{

text-align:right;

}

.center{

text-align:center;

}

</style>

</head>

<body>

<h2>

LAPORAN STOCK OPNAME

</h2>

<small>

Dicetak :
{{ now()->format('d-m-Y H:i') }}

</small>

<hr>

<table style="width:40%;">

<tr>

<td style="width:120px; font-weight:bold;">

No SO

</td>

<td>

{{ $stockOpname->opname_no }}

</td>

</tr>

<tr>

<td style="font-weight:bold;">

Tanggal

</td>

<td>

{{ $stockOpname->opname_date }}

</td>

</tr>

<tr>

<td style="font-weight:bold;">

Operator

</td>

<td>

{{ $stockOpname->user_name }}

</td>

</tr>

<tr>

<td style="font-weight:bold;">

Status

</td>

<td>

{{ $stockOpname->status }}

</td>

</tr>

</table>

<table>

<thead>

<tr>

<th>No</th>

<th>Kode</th>

<th>Nama Barang</th>

<th>Stok Sistem</th>

<th>Stok Fisik</th>

<th>Selisih</th>

<th>Keterangan</th>

</tr>

</thead>

<tbody>

@foreach($details as $i=>$item)

<tr>

<td class="center">

{{ $i+1 }}

</td>

<td>

{{ $item->product->kode_barang }}

</td>

<td>

{{ $item->product->nama_barang }}

</td>

<td class="center">

{{ $item->stock_system }}

</td>

<td class="center">

{{ $item->stock_physical }}

</td>

<td class="center">

{{ $item->difference }}

</td>

<td>

{{ $item->notes }}

</td>

</tr>

@endforeach

</tbody>

</table>

<br>

Jumlah Item :

<b>

{{ $details->count() }}

</b>

<br><br><br>

<table style="border:none">

<tr style="border:none">

<td style="border:none" class="center">

Petugas

<br><br><br><br>

(______________)

</td>

<td style="border:none" class="center">

Supervisor

<br><br><br><br>

(______________)

</td>

</tr>

</table>

<script>

window.print();

</script>

</body>

</html>