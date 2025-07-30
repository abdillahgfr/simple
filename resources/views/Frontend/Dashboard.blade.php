@extends('Frontend.Layouts.app')

@section('content')

<main class="main-wrapper clearfix">
    <!-- Page Title Area -->
    <div class="row page-title clearfix">
        <div class="page-title-left">
            <h6 class="page-title-heading mr-0 mr-r-5">Dashboard</h6>
        </div>
        <!-- /.page-title-left -->
        <div class="page-title-right d-none d-sm-inline-flex">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.html">Dashboard</a>
                </li>
                <li class="breadcrumb-item active">Home</li>
            </ol>
        </div>
        <!-- /.page-title-right -->
    </div>

    <div class="dashboard-banner" style="background-image: url('assets/img/image1.jpg'); background-size: cover; background-position: center; border-radius: 24px; margin: 24px auto; height: 500px; padding: 0; overflow: hidden;">
        <div class="dashboard-banner-overlay" style="background: rgba(0,0,0,0.5); width: 100%; height: 100%; display: flex; flex-direction: column; justify-content: center; align-items: center;">
            <h1 class="text-white font-weight-bold" style="font-size: 4.5rem; letter-spacing: 2px; text-shadow: 2px 4px 16px rgba(0,0,0,0.4);">Halo Sobat Aset</h1>
            <p class="text-white font-weight-bold" style="font-size: 1.5rem; letter-spacing: 1px; text-shadow: 1px 2px 8px rgba(0,0,0,0.3); margin-top: 12px;"># Aset Terjaga, Aset Berdayaguna</p>
        </div>
    </div>

</main>
<!-- /.main-wrappper -->
@endsection
