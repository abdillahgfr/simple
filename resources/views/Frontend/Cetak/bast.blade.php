<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>BAST {{ $kolok }}</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #000; padding: 5px; }
    </style>
</head>
<body>
    <h3>Berita Acara Serah Terima (BAST)</h3>
    <p>KOLOK SKPD: {{ $kolok }}</p>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Kode & Nama Barang</th>
                <th>Tahun Perolehan</th>
                <th>Merk</th>
                <th>Tipe</th>
                <th>Harga</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($items as $i => $row)
                <tr>
                    <td>{{ $i+1 }}</td>
                    <td>{{ $row->kobar_108 }} - {{ $row->nabar }}</td>
                    <td>{{ $row->tgloleh }}</td>
                    <td>{{ $row->merk }}</td>
                    <td>{{ $row->tipe }}</td>
                    <td>{{ number_format($row->harga,0,',','.') }}</td>
                    <td>{{ $row->disetujui }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
