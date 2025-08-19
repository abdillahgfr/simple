@extends('Frontend.Layouts.app')

@section('content')
<main class="main-wrapper clearfix">
    <div class="row page-title clearfix mb-4">
        <div class="col-md-6">
            <h6 class="page-title-heading mb-0">Detail Permohonan Aset</h6>
        </div>
        <div class="col-md-6 text-right">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Detail Permohonan Aset</a></li>
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

    @foreach ($asets as $aset)
        <div class="container mb-5">
            <div class="row bg-white shadow-sm rounded p-4">
                <!-- Kolom Gambar -->
                <div class="col-md-6 mb-3">
                    <img id="mainImage" src="{{ asset('storage/' . $aset->main_image) }}"
                        class="img-fluid rounded border"
                        alt="{{ $aset->nabar }}" width="500">
                </div>

                <!-- Kolom Informasi -->
                <div class="col-md-6">
                    <h5 class="font-weight-bold mb-3">Informasi Barang</h5>
                    <table class="table table-borderless table-sm" style="color: #838181">
                        <tr>
                            <th>GUID :</th>
                            <td>{{ $aset->guid_aset }}</td>
                        </tr>
                        <tr>
                            <th>Nama Barang :</th>
                            <td>{{ $aset->nabar }}</td>
                        </tr>
                         <tr>
                            <th>Kode Barang :</th>
                            <td>{{ $aset->kobar_108 }}</td>
                        </tr>
                         <tr>
                            <th>No Register :</th>
                            <td>{{ $aset->noreg_108 }}</td>
                        </tr>
                        <tr>
                            <th>Merk :</th>
                            <td>{{ $aset->merk }}</td>
                        </tr>
                        <tr>
                            <th>Tipe :</th>
                            <td>{{ $aset->tipe }}</td>
                        </tr>
                        <tr>
                            <th>Tahun Perolehan :</th>
                            <td>{{ \Carbon\Carbon::parse($aset->tgloleh)->year }}</td>
                        </tr>
                        <tr>
                            <th>Harga Perolehan :</th>
                            <td>Rp {{ number_format($aset->harga, 0, ',', '.') }}</td>
                        </tr>
                    </table>

                    <h5 class="font-weight-bold mt-4">Deskripsi Barang</h5>
                    <p style="color: #838181">{{ $aset->deskripsi }}</p>

                    <h6 class="font-weight-bold mt-4">Penggunaan BMD</h6>
                    <p style="color: #838181">{{ $aset->penggunaan_bmd }}</p>
                </div>

                <!-- Kolom Permohonan -->
                <div class="col-12 mt-4">
                    <h5 class="font-weight-bold">Daftar PD/UKPD Pemohon</h5>
                    <form action="{{ route('frontend.permohonanasetvalidasi') }}" method="POST" class="mt-3" id="form-aset">
                        {{-- CSRF Token --}}
                        @csrf
                        <input type="hidden" name="guid_aset" value="{{ $aset->guid_aset }}">

                        <table class="table table-bordered table-sm" style="color: #838181">
                            <thead class="thead-light">
                                <tr>
                                    <th style="text-align: center;">Kolok</th>
                                    <th style="text-align: center;">Nama PD/UKPD</th>
                                    <th style="text-align: center;">Alasan Permohonan</th>
                                    <th style="text-align: center;">Persetujuan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($aset->permohonan as $permohonan)
                                    <tr>
                                        <td style="text-align: center;">{{ $permohonan->kolok }}</td>
                                        <td style="text-align: center;">{{ $permohonan->nalok }}</td>
                                        <td style="text-align: center;">{{ $permohonan->alasan_permohonan }}</td>
                                        <td style="text-align: center;">
                                            @php
                                                $status = old('data.' . $loop->index . '.disetujui', $permohonan->disetujui ?? 'Menunggu Konfirmasi');
                                            @endphp

                                            {{-- Hidden inputs --}}
                                            <input type="hidden" name="data[{{ $loop->index }}][id]" value="{{ $permohonan->id }}">
                                            <input type="hidden" name="data[{{ $loop->index }}][guid_aset]" value="{{ $permohonan->guid_aset }}">
                                            <input type="hidden" name="data[{{ $loop->index }}][kolok]" value="{{ $permohonan->kolok }}">

                                            {{-- Tampilkan status atau opsi validasi --}}
                                            @if ($status === 'Disetujui')
                                                <button class="btn btn-success btn-sm" disabled>Approved</button>
                                            @elseif ($status === 'Tidak Disetujui')
                                                <button class="btn btn-danger btn-sm" disabled>Rejected</button>
                                            @else
                                                <div class="form-check form-check-inline">
                                                    <input type="radio"
                                                        name="data[{{ $loop->index }}][disetujui]"
                                                        value="Disetujui"
                                                        @checked($status === 'Disetujui')>
                                                    <label>Disetujui</label>

                                                    <input type="radio"
                                                        name="data[{{ $loop->index }}][disetujui]"
                                                        value="Tidak Disetujui"
                                                        @checked($status === 'Tidak Disetujui')>
                                                    <label>Ditolak</label>
                                                </div>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <div class="text-right mt-3">
                            @php
                                $allConfirmed = collect($aset->permohonan)->every(function($permohonan) {
                                    return in_array($permohonan->disetujui, ['Disetujui', 'Tidak Disetujui']);
                                });
                            @endphp
                            <button type="submit" class="btn btn-success font-weight-bold px-4" {{ $allConfirmed ? 'disabled' : '' }}>
                                Konfirmasi
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endforeach
</main>

<script>
    const form = document.getElementById('form-aset');
    if (form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();

            Swal.fire({
                title: 'Konfirmasi',
                text: "Apakah Anda yakin ingin menyetujui aset ini?",
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
