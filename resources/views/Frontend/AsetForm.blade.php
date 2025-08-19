@extends('Frontend.Layouts.app')

@section('content')

    <style>
        .form-section {
            border: 1px solid #ccc;
            padding: 15px;
            border-radius: 6px;
            background: #f9f9f9;
            margin-top: 15px;
        }
    </style>

    <main class="main-wrapper clearfix">
        <div class="row page-title clearfix">
            <div class="page-title-left">
                <h6 class="page-title-heading mt-4 mr-r-5">Form Input KIB B</h6>
            </div>
            <div class="page-title-right d-none d-sm-inline-flex">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#">Form Input KIB B</a></li>
                    <li class="breadcrumb-item active">Home</li>
                </ol>
            </div>
        </div>

        {{-- Notifikasi Error --}}
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Notifikasi Sukses --}}
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <div class="widget-list">
            <div class="row">
                <div class="col-md-12 widget-holder">
                    <div class="widget-bg">
                        <div class="widget-body clearfix">
                            <h5 class="box-title mb-3">Informasi Barang</h5>

                            {{-- Form --}}
                            <form method="GET" action="">
                                <div class="form-group">
                                    <label for="select-aset">Pilih Aset</label>
                                    <select name="guid_aset" id="select-aset" class="form-control select2"
                                            onchange="this.form.submit()">
                                        <option value="">-- Pilih Aset --</option>
                                        @foreach ($listAset as $item)
                                            <option value="{{ $item->GUID_ASET }}"
                                                {{ request('guid_aset') == $item->GUID_ASET ? 'selected' : '' }}>
                                                {{ $item->KOBAR }} : {{ $item->NOREG }} : {{ $item->NABAR }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </form>

                            {{-- Form Detail --}}
                            <form id="form-aset" method="POST" action="{{ route('frontend.formasetstore') }}" enctype="multipart/form-data">
                                @csrf
                                @if ($selectedAset)
                                <div class="form-section">
                                    <input type="hidden" name="guid_aset" value="{{ $selectedAset->GUID_ASET ?? '' }}">
                                    <input type="hidden" name="kolok" value="{{ $selectedAset->KOLOK ?? '' }}">
                                    <input type="hidden" name="nalok" value="{{ $selectedAset->NALOK ?? '' }}">
                                    <input type="hidden" name="kolokskpd" value="{{ $selectedAset->KOLOKSKPD ?? '' }}">


                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label>Kode Barang</label>
                                            <input type="text" name="kobar_108" class="form-control" style="color: #8d8d8d"
                                                value="{{ $selectedAset->KOBAR ?? '' }}" readonly>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label>No Register</label>
                                            <input type="text" name="noreg_108" class="form-control" style="color: #8d8d8d"
                                                value="{{ $selectedAset->NOREG ?? '' }}" readonly>
                                        </div>
                                    </div>

                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label>Nama Barang</label>
                                            <input type="text" name="nabar" class="form-control" style="color: #8d8d8d"
                                                value="{{ $selectedAset->NABAR ?? '' }}" readonly>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label>Merk</label>
                                            <input type="text" name="merk" class="form-control" style="color: #8d8d8d"
                                                value="{{ $selectedAset->MERK ?? '' }}" readonly>
                                        </div>
                                    </div>

                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label>Bahan</label>
                                            <input type="text" name="bahan" class="form-control" style="color: #8d8d8d"
                                                value="{{ $selectedAset->BAHAN ?? '' }}" readonly>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label>Tipe</label>
                                            <input type="text" name="tipe" class="form-control" style="color: #8d8d8d"
                                                value="{{ $selectedAset->TIPE ?? '' }}" readonly>
                                        </div>
                                    </div>

                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label>Tahun Perolehan</label>
                                            <input type="text" name="tgloleh" class="form-control" style="color: #8d8d8d"
                                                value="{{ old('tgloleh', \Carbon\Carbon::parse($selectedAset->TGL_PEROLEHAN)->format('Y-m-d')) }}"
                                                readonly>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label>Harga Perolehan</label>
                                            <input type="number" step="0.01" name="harga" class="form-control" style="color: #8d8d8d"
                                                value="{{ $selectedAset->HARGA_PEROLEHAN }}" readonly>
                                        </div>
                                    </div>

                                    <h5 class="box-title">Foto Barang*</h5>
                                    <p>Pilih dan unggah foto barang (Minimal 1 foto, maksimal 5 foto). Foto utama ditandai
                                        dengan bintang.</p>

                                    <div class="row">
                                        @php
                                            $imageKeys = ['image', 'image2', 'image3', 'image4', 'image5'];
                                        @endphp

                                        @foreach ($imageKeys as $index => $key)
                                            <div class="col-md-2 position-relative">
                                                <div class="form-group">
                                                    <div class="mb-3 image-upload-container position-relative">

                                                        {{-- Input File --}}
                                                        <input type="file" name="{{ $key }}"
                                                            id="foto-input-{{ $index + 1 }}"
                                                            class="form-control image-input" accept="image/*"
                                                            {{ $index === 0 ? 'required' : '' }}> {{-- Hanya image (pertama) yang required --}}

                                                        {{-- Pilih Gambar Utama --}}
                                                        <input type="radio" name="main_image"
                                                            id="mainImage{{ $index + 1 }}" value="{{ $key }}"
                                                            class="d-none main-image-radio"
                                                            {{ $index === 0 ? 'checked' : '' }}>

                                                        <label for="mainImage{{ $index + 1 }}" class="star-label"
                                                            data-for="mainImage{{ $index + 1 }}">
                                                            <i
                                                                class="fa-solid fa-star star-icon text-warning fs-3 mr-2"></i>Foto
                                                            Utama
                                                        </label>

                                                        {{-- Preview Gambar --}}
                                                        <img id="preview-image-{{ $index + 1 }}" src="#"
                                                            alt="Preview"
                                                            style="display:none; max-width:100%; height:auto;"
                                                            class="img-thumbnail mt-2">
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>

                                    {{-- CSS --}}
                                    <style>
                                        .star-label {
                                            cursor: pointer;
                                            top: 0px;
                                            z-index: 10;
                                        }

                                        .star-icon {
                                            font-size: 15px;
                                            opacity: 0.3;
                                            transition: opacity 0.2s ease-in-out;
                                            margin-top: 10px;
                                        }

                                        .star-label.active .star-icon {
                                            opacity: 1;
                                            margin-top: 10px
                                        }

                                        .image-upload-container {
                                            position: relative;
                                        }
                                    </style>

                                    {{-- JavaScript --}}
                                    <script>
                                        document.addEventListener("DOMContentLoaded", function() {
                                            const radios = document.querySelectorAll('.main-image-radio');
                                            const labels = document.querySelectorAll('.star-label');

                                            // Tampilkan default aktif
                                            radios.forEach(function(radio) {
                                                if (radio.checked) {
                                                    const label = document.querySelector(`label[for="${radio.id}"]`);
                                                    if (label) label.classList.add('active');
                                                }
                                            });

                                            // Klik bintang
                                            labels.forEach(function(label) {
                                                label.addEventListener('click', function() {
                                                    labels.forEach(l => l.classList.remove('active'));
                                                    label.classList.add('active');
                                                    const forId = label.getAttribute('data-for');
                                                    document.getElementById(forId).checked = true;
                                                });
                                            });

                                            // Preview gambar
                                            const inputs = document.querySelectorAll('.image-input');
                                            inputs.forEach(function(input) {
                                                input.addEventListener('change', function(e) {
                                                    const index = input.id.split('-').pop();
                                                    const preview = document.getElementById(`preview-image-${index}`);
                                                    const file = e.target.files[0];

                                                    if (file) {
                                                        const reader = new FileReader();
                                                        reader.onload = function(e) {
                                                            preview.src = e.target.result;
                                                            preview.style.display = 'block';
                                                        }
                                                        reader.readAsDataURL(file);
                                                    }
                                                });
                                            });
                                        });
                                    </script>

                                    <div class="row">
                                        <div class="col-md-6 form-group">
                                            <h5 class="box-title">Kondisi Barang Saat Ini</h5>
                                            <select class="form-control" name="kondisi" required>
                                                <option value="">-- Pilih Kondisi --</option>
                                                <option value="Baik" {{ old('kondisi') == 'Baik' ? 'selected' : '' }}>
                                                    Kondisi Baik</option>
                                                <option value="Rusak Ringan"
                                                    {{ old('kondisi') == 'Rusak Ringan' ? 'selected' : '' }}>Kondisi Rusak
                                                    Ringan</option>
                                            </select>
                                            @error('kondisi')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-6 form-group">
                                            <h5 class="box-title">Penggunaan BMD</h5>
                                            <input class="form-control" value="Idle" name="penggunaan_bmd" readonly
                                                type="text" style="padding-bottom: 16px; color: #8d8d8d;">
                                            @error('penggunaan_bmd')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <h5 class="box-title">Deskripsi Barang</h5>
                                        <textarea class="form-control" rows="3" name="deskripsi" required>{{ old('deskripsi') }}</textarea>
                                        @error('deskripsi')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <button type="submit" class="btn btn-primary mt-3">Ajukan Validasi</button>
                                </div>
                                @endif
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        new TomSelect('#select-aset', {
            create: false,
            sortField: { field: "text", direction: "asc" },
            maxOptions: null // tampilkan semua data tanpa limit
        });
    });
</script>

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
                confirmButtonText: 'Ya, Ajukan',
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
