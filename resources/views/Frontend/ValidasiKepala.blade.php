@extends('Frontend.Layouts.app')

@section('content')
    <main class="main-wrapper clearfix">
        <!-- Page Title Area -->
        <div class="row page-title clearfix">
            <div class="page-title-left">
                <h6 class="page-title-heading mr-0 mr-r-5">Validasi Aset Idle</h6>
            </div>
            <!-- /.page-title-left -->
            <div class="page-title-right d-none d-sm-inline-flex">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.html">Validasi Aset Idle</a>
                    </li>
                    <li class="breadcrumb-item active">Home</li>
                </ol>
            </div>
            <!-- /.page-title-right -->
        </div>

        <div class="col-md-12 widget-holder">
            <div class="widget-bg">
                <!-- /.widget-heading -->
                <div class="widget-body clearfix">
                    <table class="table table-editable table-responsive" data-toggle="datatables">
                        <thead>
                            <tr>
                                <th class="text-center">Kode Barang</th>
                                <th class="text-center">No. Register</th>
                                <th class="text-center">Nama Barang</th>
                                <th class="text-center">Tanggal Perolehan</th>
                                <th class="text-center">Harga Oleh</th>
                                <th class="text-center">Bahan</th>
                                <th class="text-center">Merk</th>
                                <th class="text-center">Type</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($asets as $asset)
                                <tr>
                                    <td class="text-center">{{ $asset->kobar_108 }}</td>
                                    <td class="text-center">{{ $asset->noreg_108 }}</td>
                                    <td class="text-center">{{ $asset->nabar }}</td>
                                    <td class="text-center">{{ $asset->tgloleh }}</td>
                                    <td class="text-center">{{ number_format($asset->harga, 0, ',', '.') }}</td>
                                    <td class="text-center">{{ $asset->bahan }}</td>
                                    <td class="text-center">{{ $asset->merk }}</td>
                                    <td class="text-center">{{ $asset->tipe }}</td>
                                    <td>
                                        <form action="{{ route('frontend.asetvalidasi') }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin memvalidasi aset ini?')">
                                            @csrf
                                            <input type="hidden" name="guid_aset" value="{{ $asset->guid_aset }}">
                                            <button type="submit" class="btn btn-success btn-sm" 
                                                {{ $asset->validasi_kepalaskpd === 'Validasi' ? 'disabled' : '' }}>
                                                Validasi
                                            </button>
                                        </form>

                                        <a href="{{ route('frontend.validasiasetdetail', ['guid_aset' => $asset->guid_aset]) }}" class="text-decoration-none">
                                            <button class="btn btn-info btn-sm mt-2"> Rincian</button>
                                        </a>
                                    </td>
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
@endsection
