@extends('Frontend.Layouts.app')

@section('content')
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

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
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
                        <h5 class="box-title mr-b-0">Informasi Barang</h5>
                        @if($aset)
                        <form class="form-material" method="POST" action="{{ route('frontend.formasetstore') }}" enctype="multipart/form-data">
                            @csrf
                            <div class="row mb-3 mt-5">
                                <input type="hidden" name="guid_aset" value="{{ $aset->guid_aset }}">
                                <input type="hidden" name="kobar_108" value="{{ $aset->KOBAR }}">
                                <input type="hidden" name="noreg_108" value="{{ $aset->NOREG }}">
                                <input type="hidden" name="kolokskpd" value="{{ $aset->KOLOKSKPD }}">
                                <input type="hidden" name="kolok" value="{{ $aset->KOLOK }}">
                                <input type="hidden" name="nalok" value="{{ $aset->NALOK }}">

                                <div class="col-md-6 form-group">
                                    <input class="form-control" type="text" name="nabar" value="{{ old('nabar', $aset->NABAR) }}">
                                    <label>Nama Barang</label>
                                    @error('nabar')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 form-group">
                                    <input class="form-control" type="text" name="bahan" value="{{ old('bahan', $aset->BAHAN) }}">
                                    <label>Bahan</label>
                                    @error('bahan')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 form-group">
                                    <input class="form-control" type="text" name="tgloleh" value="{{ old('tgloleh', \Carbon\Carbon::parse($aset->TGL_PEROLEHAN)->format('Y')) }}">
                                    <label>Tahun Perolehan</label>
                                    @error('tgloleh')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 form-group">
                                    <input class="form-control" type="text" name="merk" value="{{ old('merk', $aset->MERK) }}">
                                    <label>Merk</label>
                                    @error('merk')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 form-group">
                                    <input class="form-control" type="text" name="harga" value="{{ old('harga', $aset->HARGA_PEROLEHAN) }}">
                                    <label>Harga Perolehan</label>
                                    @error('harga')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 form-group">
                                    <input class="form-control" type="text" name="tipe" value="{{ old('tipe', $aset->TIPE) }}">
                                    <label>Tipe</label>
                                    @error('tipe')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <h5 class="box-title">Foto Barang*</h5>
                            <p>Pilih dan unggah foto barang (Minimal 1 foto, maksimal 5 foto). Foto utama ditandai dengan border hijau.</p>

                            <div class="row">
                                @php
                                    $imageKeys = ['image', 'image2', 'image3', 'image4', 'image5'];
                                @endphp

                                @foreach ($imageKeys as $index => $key)
                                    <div class="col-md-2 position-relative">
                                        <div class="form-group">
                                            <div class="mb-3 image-upload-container position-relative">

                                                {{-- Input File --}}
                                                <input 
                                                    type="file" 
                                                    name="{{ $key }}" 
                                                    id="foto-input-{{ $index + 1 }}" 
                                                    class="form-control image-input" 
                                                    accept="image/*" 
                                                    {{ $index === 0 ? 'required' : '' }}> {{-- Hanya image (pertama) yang required --}}

                                                {{-- Pilih Gambar Utama --}}
                                                <input 
                                                    type="radio" 
                                                    name="main_image" 
                                                    id="mainImage{{ $index + 1 }}" 
                                                    value="{{ $key }}" 
                                                    class="d-none main-image-radio" 
                                                    {{ $index === 0 ? 'checked' : '' }}>
                                                
                                                <label 
                                                    for="mainImage{{ $index + 1 }}" 
                                                    class="star-label" 
                                                    data-for="mainImage{{ $index + 1 }}">
                                                    <i class="fa-solid fa-star star-icon text-warning fs-3 mr-2"></i>Foto Utama
                                                </label>

                                                {{-- Preview Gambar --}}
                                                <img id="preview-image-{{ $index + 1 }}" src="#" alt="Preview" style="display:none; max-width:100%; height:auto;" class="img-thumbnail mt-2">
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
                                document.addEventListener("DOMContentLoaded", function () {
                                    const radios = document.querySelectorAll('.main-image-radio');
                                    const labels = document.querySelectorAll('.star-label');

                                    // Tampilkan default aktif
                                    radios.forEach(function (radio) {
                                        if (radio.checked) {
                                            const label = document.querySelector(`label[for="${radio.id}"]`);
                                            if (label) label.classList.add('active');
                                        }
                                    });

                                    // Klik bintang
                                    labels.forEach(function (label) {
                                        label.addEventListener('click', function () {
                                            labels.forEach(l => l.classList.remove('active'));
                                            label.classList.add('active');
                                            const forId = label.getAttribute('data-for');
                                            document.getElementById(forId).checked = true;
                                        });
                                    });

                                    // Preview gambar
                                    const inputs = document.querySelectorAll('.image-input');
                                    inputs.forEach(function (input) {
                                        input.addEventListener('change', function (e) {
                                            const index = input.id.split('-').pop();
                                            const preview = document.getElementById(`preview-image-${index}`);
                                            const file = e.target.files[0];

                                            if (file) {
                                                const reader = new FileReader();
                                                reader.onload = function (e) {
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
                                        <option value="Baik" {{ old('kondisi') == 'Baik' ? 'selected' : '' }}>Kondisi Baik</option>
                                        <option value="Rusak Ringan" {{ old('kondisi') == 'Rusak Ringan' ? 'selected' : '' }}>Kondisi Rusak Ringan</option>
                                        <option value="Rusak Berat" {{ old('kondisi') == 'Rusak Berat' ? 'selected' : '' }}>Kondisi Rusak Berat</option>
                                    </select>
                                    @error('kondisi')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 form-group">
                                    <h5 class="box-title">Penggunaan BMD</h5>
                                    <input class="form-control" value="Idle" name="penggunaan_bmd" readonly type="text">
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

                            <div class="form-actions btn-list">
                                <button class="btn btn-success" type="submit">Kirim</button>
                                <a href="{{ url()->previous() }}" class="btn btn-outline-default">Batal</a>
                            </div>
                        </form>
                        @else
                        <p class="text-danger">Data aset tidak ditemukan.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

@push('scripts')
<style>
    .image-upload-container {
        border: 1px solid #ddd; /* Default border */
        padding: 10px;
        border-radius: 5px;
        transition: border-color 0.2s ease-in-out;
    }
    .image-upload-container.main-selected {
        border: 3px solid #28a745; /* Green border for main image */
    }
    .image-upload-container img.img-thumbnail {
        cursor: pointer; /* Indicate clickable image for selection */
    }
</style>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const imageInputs = document.querySelectorAll('.image-input');
        const mainImageRadios = document.querySelectorAll('.main-image-radio');

        // Function to handle image preview
        imageInputs.forEach(input => {
            input.addEventListener('change', function(event) {
                const index = this.id.split('-')[2]; // Get the number from foto-input-X
                const preview = document.getElementById(`preview-image-${index}`);
                const [file] = event.target.files;

                if (file) {
                    preview.src = URL.createObjectURL(file);
                    preview.style.display = 'block';
                    // Automatically select the radio button when a new image is uploaded
                    document.getElementById(`mainImage${index}`).checked = true;
                    document.getElementById(`mainImage${index}`).dispatchEvent(new Event('change')); // Trigger change event
                } else {
                    preview.src = '#'; // Clear source
                    preview.style.display = 'none';
                    // If image is cleared, uncheck its radio and maybe select the first available
                    const radioForThisInput = document.getElementById(`mainImage${index}`);
                    if (radioForThisInput.checked) {
                        radioForThisInput.checked = false;
                        // Attempt to check the first valid radio if this one was unchecked
                        const firstAvailableRadio = document.querySelector('.main-image-radio:not(:disabled)');
                        if (firstAvailableRadio && !firstAvailableRadio.checked) {
                            firstAvailableRadio.checked = true;
                            firstAvailableRadio.dispatchEvent(new Event('change'));
                        } else if (!firstAvailableRadio) { // If no other radio is available, clear border
                            document.querySelectorAll('.image-upload-container').forEach(container => {
                                container.classList.remove('main-selected');
                            });
                        }
                    }
                }
            });
        });

        // Function to handle main image selection (green border)
        mainImageRadios.forEach(radio => {
            radio.addEventListener('change', function() {
                // Remove green border from all containers first
                document.querySelectorAll('.image-upload-container').forEach(container => {
                    container.classList.remove('main-selected');
                });

                // Add green border to the selected main image's container
                // The radio button value should match the input name: 'image', 'image2', etc.
                const selectedImageName = this.value;
                const containerId = `container-${selectedImageName}`;
                const selectedContainer = document.getElementById(containerId);
                if (selectedContainer) {
                    selectedContainer.classList.add('main-selected');
                }
            });
        });

        // Event listener for clicking on image previews to select main image
        document.querySelectorAll('.image-upload-container img.img-thumbnail').forEach(img => {
            img.addEventListener('click', function() {
                const index = this.id.split('-')[2];
                const radio = document.getElementById(`mainImage${index}`);
                if (radio) {
                    radio.checked = true;
                    radio.dispatchEvent(new Event('change')); // Trigger change event for styling
                }
            });
        });

        // Initialize green border on page load if a radio button is checked (e.g., the first one)
        const initiallyCheckedRadio = document.querySelector('.main-image-radio:checked');
        if (initiallyCheckedRadio) {
            const selectedImageName = initiallyCheckedRadio.value;
            const containerId = `container-${selectedImageName}`;
            const selectedContainer = document.getElementById(containerId);
            if (selectedContainer) {
                selectedContainer.classList.add('main-selected');
            }
        }
    });
</script>
@endpush
@endsection