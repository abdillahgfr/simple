@extends('Frontend.Layouts.app')

@section('content')
<main class="main-wrapper clearfix">
    <!-- Page Title -->
    <div class="row page-title clearfix">
        <div class="page-title-left">
            <h6 class="page-title-heading mr-0 mr-r-5">Display Aset Idle</h6>
        </div>
        <div class="page-title-right d-none d-sm-inline-flex">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Display Aset Idle</a></li>
                <li class="breadcrumb-item active">Home</li>
            </ol>
        </div>
    </div>

    <!-- Filter + Aset -->
    <div class="row">
        <!-- Sidebar Filter -->
        <div class="col-md-3">
            <form method="GET" action="{{ route('frontend.asetidle') }}">
                <div class="card p-3 shadow-sm">
                    <h6><strong>Pencarian Lebih Detail</strong></h6>

                    {{-- Nama Barang --}}
                    <label class="mt-3">Nama Barang</label>
                    <input type="text" class="form-control form-control-sm" name="nabar" placeholder="Search..."
                        value="{{ request('nabar') }}">

                    {{-- Merk --}}
                    <label class="mt-3">Merk</label>
                    <input type="text" class="form-control form-control-sm" name="merk" placeholder="Search..."
                        value="{{ request('merk') }}">

                    {{-- Kondisi --}}
                    <label class="mt-3">Kondisi</label>
                    <select class="form-control form-control-sm" name="kondisi">
                        <option value="">- Semua -</option>
                        <option value="Baik" {{ request('kondisi') == 'Baik' ? 'selected' : '' }}>Baik</option>
                        <option value="Rusak Ringan" {{ request('kondisi') == 'Rusak Ringan' ? 'selected' : '' }}>Rusak Ringan</option>
                        <option value="Rusak Berat" {{ request('kondisi') == 'Rusak Berat' ? 'selected' : '' }}>Rusak Berat</option>
                    </select>

                    {{-- Tahun Perolehan --}}
                    <label class="mt-3">Tahun Perolehan</label>
                    <select class="form-control form-control-sm" name="tahun">
                        <option value="">- Semua -</option>
                        @foreach ($tahunList as $tahun)
                            <option value="{{ $tahun }}" {{ request('tahun') == $tahun ? 'selected' : '' }}>{{ $tahun }}</option>
                        @endforeach
                    </select>

                    <button class="btn btn-primary btn-sm mt-3 w-100">Cari</button>
                </div>
            </form>
        </div>

        <!-- Konten Aset -->
        <div class="col-md-9">
            <div class="row">
                @forelse ($asets as $aset)
                    <div class="col-md-4 mb-4">
                        <div class="card h-100 shadow-sm">
                            <img src="{{ asset('storage/' . $aset->main_image) }}"
                                class="card-img-top" alt="{{ $aset->nabar }}"
                                style="height: 280px; border-radius: 10px; object-fit: fill;">
                            <div class="card-body">
                                <a href="{{ route('frontend.rincianaset', ['guid_aset' => $aset->guid_aset]) }}" class="text-decoration-none">
                                    <h6 class="card-title text-uppercase text-primary">{{ $aset->nabar }}</h6></a>
                                <p class="mb-1">
                                    <strong>{{ $aset->tgloleh ? \Carbon\Carbon::parse($aset->tgloleh)->year : '-' }}</strong>
                                    | {{ $aset->merk ?? '-' }}
                                </p>
                                <p class="mb-1">{{ $aset->nalok ?? 'PEMPROV DKI JAKARTA' }}</p>

                                @php
                                    $badgeClass = 'bg-secondary';
                                    if (stripos($aset->kondisi, 'Baik') !== false) $badgeClass = 'bg-success';
                                    elseif (stripos($aset->kondisi, 'Rusak Ringan') !== false) $badgeClass = 'bg-warning';
                                    elseif (stripos($aset->kondisi, 'Rusak Berat') !== false) $badgeClass = 'bg-danger';
                                @endphp

                                <span class="badge {{ $badgeClass }} text-white">
                                    {{ strtoupper($aset->kondisi ?? 'UNKNOWN') }}
                                </span>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <p class="text-muted">Tidak ada data aset ditemukan.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</main>
@endsection
