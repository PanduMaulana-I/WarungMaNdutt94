<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
        }
        h2 {
            margin-bottom: 0;
            font-size: 20px;
            font-weight: bold;
        }
        .small {
            font-size: 12px;
            margin-bottom: 10px;
            margin-top: 4px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 14px;
        }
        th, td {
            border: 1px solid #000;
            padding: 6px 8px;
            font-size: 12px;
        }
        th {
            background: #f2f2f2;
            font-weight: bold;
            text-align: left;
        }
    </style>
</head>
<body>

<h2>Laporan Penjualan</h2>

<p class="small">
    Dari: <strong>{{ $from->format('d M Y') }}</strong><br>
    Sampai: <strong>{{ $to->format('d M Y') }}</strong>
</p>

<table>
    <thead>
        <tr>
            <th>No Order</th>
            <th>Tanggal</th>
            <th>Customer</th>
            <th>Status</th>
            <th>Menu</th>
            <th>Qty</th>
            <th>Harga</th>
            <th>Subtotal</th>
        </tr>
    </thead>

    <tbody>
        @foreach($rows as $r)
        <tr>
            <td>{{ $r['order_number'] }}</td>
            <td>{{ $r['date'] }}</td>
            <td>{{ $r['customer'] }}</td>
            <td>{{ $r['status'] }}</td>
            <td>{{ $r['menu'] }}</td>
            <td>{{ $r['qty'] }}</td>
            <td>Rp {{ number_format($r['price'], 0, ',', '.') }}</td>
            <td>Rp {{ number_format($r['subtotal'], 0, ',', '.') }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

</body>
</html>
