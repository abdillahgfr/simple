@extends('Frontend.Layouts.app')

@section('content')
<main class="main-wrapper clearfix">
    <!-- Page Title -->
    <div class="row page-title clearfix mb-4">
        <div class="col-md-6">
            <h6 class="page-title-heading mb-0">Cetak BAST BMD</h6>
        </div>
        <div class="col-md-6 text-right">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Cetak BAST BMD</a></li>
                <li class="breadcrumb-item active">Home</li>
            </ol>
        </div>
    </div>

    <h5 class="mb-3 font-weight-bold">PERMOHONAN PENGGUNAAN ASET IDLE</h5>

    @php
        $userKolok = session('user')->kolok ?? null;
    @endphp

    @foreach ($grouped as $kolok => $items)
        @if ($kolok == $userKolok)
            @php
                $firstItem = collect($items)->first();
                $opdName = ($firstItem['nalok'] ?? $firstItem['pemilik_nalok'] ?? '-') . ' (' . $firstItem['kolok'] . ')';
                $adaDisetujui = collect($items)->where('disetujui', 'Disetujui')->count() > 0;
            @endphp

            <div class="card mb-4 shadow-sm">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <strong>{{ $opdName }}</strong>
                    <button onclick="window.location='{{ route('cetak.bast', $kolok) }}'"
                        class="btn btn-sm {{ $adaDisetujui ? 'btn-success' : 'btn-secondary' }}"
                        {{ $adaDisetujui ? '' : 'disabled' }}>
                        Cetak BAST
                    </button>
                </div>
                <div class="card-body p-0">
                    <table class="table table-bordered mb-0">
                        <thead class="thead-light">
                            <tr>
                                <th>No</th>
                                <th>Kode & Nama Barang</th>
                                <th>Tahun Perolehan</th>
                                <th>Bahan</th>
                                <th>Merk</th>
                                <th>Tipe</th>
                                <th>Harga Perolehan</th>
                                <th>Alasan Permohonan</th>
                                <th>Status Permohonan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($items as $i => $row)
                                <tr>
                                    <td>{{ $i + 1 }}</td>
                                    <td>{{ $row['kobar_108'] }} - {{ $row['nabar'] }}</td>
                                    <td>{{ $row['tgloleh'] }}</td>
                                    <td>{{ $row['bahan'] }}</td>
                                    <td>{{ $row['merk'] }}</td>
                                    <td>{{ $row['tipe'] }}</td>
                                    <td>{{ number_format($row['harga'], 0, ',', '.') }}</td>
                                    <td>{{ $row['alasan_permohonan'] }}</td>
                                    <td>
                                        @if ($row['disetujui'] == 'Disetujui')
                                            <span class="badge badge-success">Disetujui</span>
                                        @elseif($row['disetujui'] == 'Tidak Disetujui')
                                            <span class="badge badge-danger">Tidak Disetujui</span>
                                        @else
                                            <span class="badge badge-warning">Menunggu Konfirmasi</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
    @endforeach

</main>
@endsection
