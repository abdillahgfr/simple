@extends('Frontend.Layouts.app')

@section('content')
<main class="main-wrapper clearfix">
    <!-- Page Title -->
    <div class="row page-title clearfix mb-4">
        <div class="col-md-6">
            <h6 class="page-title-heading mb-0">Display Aset</h6>
        </div>
        <div class="col-md-6 text-right">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Display Aset</a></li>
                <li class="breadcrumb-item active">Home</li>
            </ol>
        </div>
    </div>

    <!-- Konten Aset -->
    <div class="container mb-5">
        <div class="row bg-white shadow rounded p-4">
            <!-- Kolom Gambar (kiri) -->
            <div class="col-md-6">
                <!-- Gambar utama -->
                <img id="mainImage" src="{{ asset('storage/' . $aset->main_image) }}" class="img-fluid rounded mb-3" width="500" alt="{{ $aset->nabar }}">

                <!-- Thumbnail -->
                <div class="d-flex mb-3">
                    @if($aset->image2)
                        <img src="{{ asset('storage/' . $aset->image2) }}" 
                            class="img-thumbnail mr-2 thumb-img" 
                            style="width: 80px; height: 80px;" 
                            alt="{{ $aset->nabar }}" 
                            onclick="changeMainImage(this)">
                    @endif
                    @if($aset->image3)
                        <img src="{{ asset('storage/' . $aset->image3) }}" 
                            class="img-thumbnail mr-2 thumb-img" 
                            style="width: 80px; height: 80px;" 
                            alt="{{ $aset->nabar }}" 
                            onclick="changeMainImage(this)">
                    @endif
                    @if($aset->image4)
                        <img src="{{ asset('storage/' . $aset->image4) }}" 
                            class="img-thumbnail thumb-img" 
                            style="width: 80px; height: 80px;" 
                            alt="{{ $aset->nabar }}" 
                            onclick="changeMainImage(this)">
                    @endif
                    @if($aset->image5)
                        <img src="{{ asset('storage/' . $aset->image5) }}" 
                            class="img-thumbnail thumb-img" 
                            style="width: 80px; height: 80px;" 
                            alt="{{ $aset->nabar }}" 
                            onclick="changeMainImage(this)">
                    @endif
                </div>

                <div class="text-muted small">
                    <i class="fa fa-eye"></i> Dilihat: {{ $aset->jumlah_dilihat }} &nbsp;
                    <i class="fa fa-user"></i> PD/UKPD Peminat: {{ $aset->jumlah_peminat }}
                </div>
            </div>

            <!-- Script -->
            <script>
                function changeMainImage(thumb) {
                    const mainImg = document.getElementById('mainImage');
                    mainImg.src = thumb.src;
                }
            </script>

            <!-- Kolom Detail Aset (kanan) -->
            <div class="col-md-6">
                @php
                    $badgeClass = 'badge-secondary';

                    if (strtolower($aset->kondisi) === 'Baik') {
                        $badgeClass = 'badge-success';
                    } elseif (strtolower($aset->kondisi) === 'Rusak Ringan') {
                        $badgeClass = 'badge-warning'; // kuning
                    } elseif (strtolower($aset->kondisi) === 'Rusak Berat') {
                        $badgeClass = 'badge-danger'; // merah
                    }
                @endphp

                <div class="d-flex justify-content-between align-items-start mb-3">
                    <h4 class="mb-0 font-weight-bold">{{ $aset->nabar }}</h4>
                    <span class="badge {{ $badgeClass }} p-2 text-uppercase">
                        KONDISI {{ strtoupper($aset->kondisi) }}
                    </span>
                </div>


                <table class="table table-sm table-borderless mb-4" style="color: #838181">
                    <tr>
                        <th class="w-50">PD/UKPD Pengguna Barang</th>
                        <td>{{ $aset->nalok }}</td>
                    </tr>
                    <tr>
                        <th>Harga</th>
                        <td>Rp{{ number_format($aset->harga, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <th>Tanggal Perolehan</th>
                        <td>{{ \Carbon\Carbon::parse($aset->tgloleh)->translatedFormat('d F Y') }}</td>
                    </tr>
                    <tr>
                        <th>Merek</th>
                        <td>{{ $aset->merk }}</td>
                    </tr>
                    <tr>
                        <th>Tipe</th>
                        <td>{{ $aset->tipe }}</td>
                    </tr>
                </table>

                {{-- <div class="form-group">
                    <label for="alasan" class="font-weight-bold">Alasan Permohonan</label>
                    <textarea id="alasan" class="form-control" rows="4" placeholder="Tulis alasan permohonan di sini..."></textarea>
                </div> --}}

                <a href="{{ route('frontend.validasiaset')}}">
                    <button class="btn btn-success btn-block font-weight-bold">
                        Kembali
                    </button>
                </a>
            </div>
        </div>
    </div>
</main>
@endsection
