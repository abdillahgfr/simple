@extends('Frontend.Layouts.app')

@section('content')
    <main class="main-wrapper clearfix">
        <!-- Page Title -->
        <div class="row page-title clearfix">
            <div class="page-title-left">
                <h6 class="page-title-heading mr-0 mr-r-5">Permohonan Aset Idle</h6>
            </div>
            <div class="page-title-right d-none d-sm-inline-flex">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#">Permohonan Aset Idle</a></li>
                    <li class="breadcrumb-item active">Home</li>
                </ol>
            </div>
        </div>

        <!-- Filter + Aset -->
        <div class="row">
            <!-- Konten Aset -->
            <div class="col-md-12">
                <div class="row">
                    @forelse ($asets as $aset)
                        <div class="col-md-4 mb-4">
                            <div class="card h-100 shadow-sm position-relative">
                                <div class="position-relative">
                                    <img src="{{ asset('storage/' . $aset->main_image) }}"
                                        class="card-img-top" alt="{{ $aset->nabar }}"
                                        style="height: 280px; border-radius: 10px; object-fit: fill;">
                                    
                                    {{-- Notifikasi jumlah permohonan --}}
                                    @if (isset($jumlahPermohonan[$aset->guid_aset]))
                                        <div class="position-absolute top-0 end-0 translate-middle badge rounded-circle bg-danger"
                                            style="width: 30px; height: 30px; font-size: 14px; margin-top: 8px; margin-right: 8px; float: right;">
                                            {{ $jumlahPermohonan[$aset->guid_aset] }}
                                        </div>
                                    @endif
                                </div>

                                <div class="card-body">
                                    <a href="{{ route('frontend.permohonandetail', ['guid_aset' => $aset->guid_aset]) }}" class="text-decoration-none">
                                        <h6 class="card-title text-uppercase text-primary">{{ $aset->nabar }}</h6>
                                    </a>
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
