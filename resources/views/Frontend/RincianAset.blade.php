@extends('Frontend.Layouts.app')

@section('content')
<main class="main-wrapper clearfix">
    <!-- Page Title -->
    <div class="row page-title clearfix mb-4">
        <div class="col-md-6">
            <h6 class="page-title-heading mb-0">Display Aset Idle</h6>
        </div>
        <div class="col-md-6 text-right">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Display Aset Idle</a></li>
                <li class="breadcrumb-item active">Home</li>
            </ol>
        </div>
    </div>

    {{-- Notifikasi Sukses --}}
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <!-- Konten Aset -->
    <div class="container mb-5">
        <div class="row bg-white shadow rounded p-4">
            <!-- Kolom Gambar (kiri) -->
            <div class="col-md-6">
                <!-- Gambar utama -->
                <img id="mainImage" src="{{ asset('storage/' . $aset->main_image) }}" class="img-fluid rounded mb-3" width="500" alt="{{ $aset->nabar }}">

                <!-- Thumbnail -->
                <div class="d-flex mb-3">
                    @if($aset->image)
                        <img src="{{ asset('storage/' . $aset->image) }}" 
                            class="img-thumbnail mr-2 thumb-img" 
                            style="width: 80px; height: 80px;" 
                            alt="{{ $aset->nabar }}" 
                            onclick="changeMainImage(this)">
                    @endif
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
                </div>
                @if($aset->image5)
                        <img src="{{ asset('storage/' . $aset->image5) }}" 
                            class="img-thumbnail thumb-img" 
                            style="width: 80px; height: 80px;" 
                            alt="{{ $aset->nabar }}" 
                            onclick="changeMainImage(this)">
                    @endif

                <div class="text-muted small">
                    <i class="fa fa-eye"></i> Dilihat: {{ $aset->jumlah_dilihat }} &nbsp;
                    <i class="fa fa-user"></i> PD/UKPD Peminat: {{ $aset->jumlahPermohonan }}
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
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <h4 class="mb-0 font-weight-bold">{{ $aset->nabar }}</h4>
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

                <table class="table table-sm table-borderless mb-4" style="color: #838181">
                    <tr>
                        <th class="w-50">Nama Barang</th>
                        <td>{{ $aset->nabar }}</td>
                    </tr>
                    <tr>
                        <th class="w-50">Kode Barang</th>
                        <td>{{ $aset->kobar_108 }}</td>
                    </tr>
                    <tr>
                        <th class="w-50">No Register</th>
                        <td>{{ $aset->noreg_108 }}</td>
                    </tr>
                    <tr>
                        <th class="w-50">PD/UKPD Pengguna Barang</th>
                        <td>{{ $aset->nalok }}</td>
                    </tr>
                    <tr>
                        <th>Harga</th>
                        <td>Rp {{ number_format($aset->harga, 0, ',', '.') }}</td>
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
                    <tr>
                        <th>Deskripsi</th>
                        <td>{{ $aset->deskripsi }}</td>
                    </tr>
                </table>
                <form class="form-material" method="POST" action="{{ route('frontend.rincianasetsubmit') }}" enctype="multipart/form-data" id="form-aset">
                    @csrf
                    @php
                        $user = session('user');
                    @endphp
                    <div class="form-group">
                        <input type="hidden" name="guid_aset" value="{{ $aset->guid_aset }}">
                        <input type="hidden" name="kobar_108" value="{{ $aset->kobar_108 }}">
                        <input type="hidden" name="noreg_108" value="{{ $aset->noreg_108 }}">
                        <input type="hidden" name="nabar" value="{{ $aset->nabar }}">
                        <input type="hidden" name="kolokskpd" value="{{ $user->kolokskpd }}">
                        <input type="hidden" name="kolok" value="{{ $user->kolok }}">
                        <input type="hidden" name="nalok" value="{{ $user->nalok ?? $aset->nalok }}">
                        <input type="hidden" name="bahan" value="{{ $aset->bahan }}">
                        <input type="hidden" name="merk" value="{{ $aset->merk }}">
                        <input type="hidden" name="tipe" value="{{ $aset->tipe }}">
                        <input type="hidden" name="harga" value="{{ $aset->harga }}">
                        <input type="hidden" name="tgloleh" value="{{ $aset->tgloleh }}">
                        <input type="hidden" name="kondisi" value="{{ $aset->kondisi }}">
                        <input type="hidden" name="deskripsi" value="{{ $aset->deskripsi }}">
                        <input type="hidden" name="penggunaan_bmd" value="{{ $aset->penggunaan_bmd }}">

                        <label for="alasan" class="font-weight-bold">Alasan Permohonan</label>
                        <textarea id="alasan" name="alasan_permohonan" class="form-control" rows="4" placeholder="Tulis alasan permohonan di sini..." required></textarea>
                    </div>

                    @if($sudahMengajukan)
                        <button class="btn btn-secondary btn-block font-weight-bold" disabled>
                            Permohonan Sudah Diajukan
                        </button>
                    @else
                        <button class="btn btn-success btn-block font-weight-bold">
                            Ajukan Permohonan
                        </button>
                    @endif
                </form>
            </div>
        </div>
    </div>
</main>


<script>
    const form = document.getElementById('form-aset');
    if (form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();

            Swal.fire({
                title: 'Konfirmasi',
                text: "Apakah Anda yakin ingin mengajukan aset ini?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya',
                cancelButtonText: 'Batal',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    }
</script>

@endsection
