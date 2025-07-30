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
                            @foreach($results as $aset)
                                <tr>
                                    <td class="text-center">{{ $aset->KOBAR }}</td>
                                    <td class="text-center">{{ $aset->NOREG }}</td>
                                    <td class="text-center">{{ $aset->NABAR }}</td>
                                    <td class="text-center">{{ $aset->TGL_PEROLEHAN }}</td>
                                    <td class="text-center">{{ number_format($aset->HARGA_PEROLEHAN, 0, ',', '.') }}</td>
                                    <td class="text-center">{{ $aset->BAHAN }}</td>
                                    <td class="text-center">{{ $aset->MERK }}</td>
                                    <td class="text-center">{{ $aset->TIPE }}</td>
                                    <td class="text-center">
                                        <i class="material-icons">add_box</i>
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
