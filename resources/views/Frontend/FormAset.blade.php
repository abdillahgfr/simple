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
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <div class="mb-3 image-upload-container" id="container-image"> {{-- Updated ID for main_image value --}}
                                            <input type="file" name="image" id="foto-input-1" class="form-control image-input" accept="image/*" required>
                                            <div class="form-check ml-4 mt-2">
                                                <input class="form-check-input main-image-radio" type="radio" name="main_image" id="mainImage1" value="image" checked> {{-- Value set to 'image' matching input name --}}
                                                <label class="form-check-label" for="mainImage1">
                                                    Foto Utama
                                                </label>
                                            </div>
                                            <img id="preview-image-1" src="#" alt="Preview" style="display:none; max-width:100%; height:auto;" class="img-thumbnail mt-2">
                                            @error('image')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <div class="mb-3 image-upload-container" id="container-image2">
                                            <input type="file" name="image2" id="foto-input-2" class="form-control image-input" accept="image/*"> {{-- Removed 'required' --}}
                                            <div class="form-check ml-4 mt-2">
                                                <input class="form-check-input main-image-radio" type="radio" name="main_image" id="mainImage2" value="image2">
                                                <label class="form-check-label" for="mainImage2">
                                                    Foto Utama
                                                </label>
                                            </div>
                                            <img id="preview-image-2" src="#" alt="Preview" style="display:none; max-width:100%; height:auto;" class="img-thumbnail mt-2">
                                            @error('image2')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <div class="mb-3 image-upload-container" id="container-image3">
                                            <input type="file" name="image3" id="foto-input-3" class="form-control image-input" accept="image/*"> {{-- Removed 'required' --}}
                                            <div class="form-check ml-4 mt-2">
                                                <input class="form-check-input main-image-radio" type="radio" name="main_image" id="mainImage3" value="image3">
                                                <label class="form-check-label" for="mainImage3">
                                                    Foto Utama
                                                </label>
                                            </div>
                                            <img id="preview-image-3" src="#" alt="Preview" style="display:none; max-width:100%; height:auto;" class="img-thumbnail mt-2">
                                            @error('image3')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <div class="mb-3 image-upload-container" id="container-image4">
                                            <input type="file" name="image4" id="foto-input-4" class="form-control image-input" accept="image/*"> {{-- Removed 'required' --}}
                                            <div class="form-check ml-4 mt-2">
                                                <input class="form-check-input main-image-radio" type="radio" name="main_image" id="mainImage4" value="image4">
                                                <label class="form-check-label" for="mainImage4">
                                                    Foto Utama
                                                </label>
                                            </div>
                                            <img id="preview-image-4" src="#" alt="Preview" style="display:none; max-width:100%; height:auto;" class="img-thumbnail mt-2">
                                            @error('image4')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <div class="mb-3 image-upload-container" id="container-image5">
                                            <input type="file" name="image5" id="foto-input-5" class="form-control image-input" accept="image/*"> {{-- Removed 'required' --}}
                                            <div class="form-check ml-4 mt-2">
                                                <input class="form-check-input main-image-radio" type="radio" name="main_image" id="mainImage5" value="image5">
                                                <label class="form-check-label" for="mainImage4">
                                                    Foto Utama
                                                </label>
                                            </div>
                                            <img id="preview-image-4" src="#" alt="Preview" style="display:none; max-width:100%; height:auto;" class="img-thumbnail mt-2">
                                            @error('image4')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

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