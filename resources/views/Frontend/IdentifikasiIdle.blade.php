@extends('Frontend.Layouts.app')

@section('content')
    <main class="main-wrapper clearfix">
        <!-- Page Title Area -->
        <div class="row page-title clearfix">
            <div class="page-title-left">
                <h6 class="page-title-heading mr-0 mr-r-5">Identifikasi Aset Idle</h6>
            </div>
            <!-- /.page-title-left -->
            <div class="page-title-right d-none d-sm-inline-flex">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.html">Identifikasi Aset Idle</a>
                    </li>
                    <li class="breadcrumb-item active">Home</li>
                </ol>
            </div>
            <!-- /.page-title-right -->
        </div>

        {{-- Notifikasi Sukses --}}
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <div class="col-md-12 widget-holder">
            <div class="widget-bg">
                <!-- /.widget-heading -->
                <div class="widget-body clearfix">
                    <a href="{{ route('frontend.asetform') }}" class="btn btn-primary">Tambah</a>
                    <table class="table table-editable table-responsive" data-toggle="datatables">
                        <thead>
                            <tr>
                                <th class="text-center">Kode Barang</th>
                                <th class="text-center">No. Register</th>
                                <th class="text-center">Foto</th>
                                <th class="text-center">Nama Barang</th>
                                <th class="text-center">Tanggal Perolehan</th>
                                <th class="text-center">Harga Oleh</th>
                                <th class="text-center">Bahan</th>
                                <th class="text-center">Merk</th>
                                <th class="text-center">Type</th>
                                <th class="text-center">Action</th>
                                <th class="text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($asets as $aset)
                                <tr>
                                    <td class="text-center">{{ $aset->kobar_108 }}</td>
                                    <td class="text-center">{{ $aset->noreg_108 }}</td>
                                    <td class="text-center">
                                        <img src="{{ asset('storage/' . $aset->main_image) }}" 
                                        class="img-thumbnail mr-2 thumb-img" 
                                        style="width: 100px;" 
                                        alt="{{ $aset->nabar }}">
                                    </td>
                                    <td class="text-center">{{ $aset->nabar }}</td>
                                    <td class="text-center">{{ \Carbon\Carbon::parse($aset->tgloleh)->format('Y-m-d') }}</td>
                                    <td class="text-center">{{ number_format($aset->harga, 0, ',', '.') }}</td>
                                    <td class="text-center">{{ $aset->bahan }}</td>
                                    <td class="text-center">{{ $aset->merk }}</td>
                                    <td class="text-center">{{ $aset->tipe }}</td>
                                    <td class="text-center">
                                        @php
                                            $user = session('user');
                                        @endphp
                                        
                                        @if($user && $user->idgroup === 'Kepala' && $aset->validasi_kepalaskpd !== 'Validasi')
                                        <form action="{{ route('frontend.asetvalidasi') }}" method="POST" id="form-aset">
                                            @csrf
                                            <input type="hidden" name="guid_aset" value="{{ $aset->guid_aset }}">
                                            <button type="submit" class="btn btn-success btn-sm">
                                                Validasi
                                            </button>
                                        </form>
                                        @endif

                                        <a href="{{ route('frontend.identikasiasetdetail', ['guid_aset' => $aset->guid_aset]) }}" class="text-decoration-none">
                                            <button class="btn btn-info btn-sm mt-2"> Rincian</button>
                                        </a>
                                    </td>
                                    <td class="text-center">{{ $aset->validasi_kepalaskpd }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <!-- /.widget-body -->
            </div>
            <!-- /.widget-bg -->
        </div>
        <!-- /.widget-holder -->

    </main>
    <!-- /.main-wrappper -->

<script>
    const form = document.getElementById('form-aset');
    if (form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();

            Swal.fire({
                title: 'Konfirmasi',
                text: "Apakah Anda yakin ingin memvalidasi aset ini?",
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
