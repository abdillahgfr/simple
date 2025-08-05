@extends('Frontend.Layouts.app')

@section('content')
    <main class="main-wrapper clearfix">
        <!-- Page Title Area -->
        <div class="row page-title clearfix">
            <div class="page-title-left">
                <h6 class="page-title-heading mr-0 mr-r-5">Profile</h6>
            </div>
            <!-- /.page-title-left -->
            <div class="page-title-right d-none d-sm-inline-flex">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.html">Profile</a>
                    </li>
                    <li class="breadcrumb-item active">Home</li>
                </ol>
            </div>
            <!-- /.page-title-right -->
        </div>

        <div class="widget-list">
    <div class="row">
        <div class="col-12 widget-holder widget-full-content">
            <div class="widget-bg">
                <div class="widget-body clearfix">
                    <div class="widget-user-profile">
                        <figure class="profile-wall-img">
                            <img src="assets/demo/user-widget-bg.jpeg" alt="User Wall">
                        </figure>
                        <div class="profile-body text-center">
                            <figure class="profile-user-avatar thumb-md mx-auto">
                                <img src="assets/img/default-avatar.jpg" alt="User Avatar">
                            </figure>
                            <h6 class="h3 profile-user-name mb-1">Profil Pengguna</h6>
                            <small class="profile-user-address">Informasi Profil</small>

                            <hr class="profile-seperator">

                            <!-- Profile Edit Form -->
                            <form method="POST" action="" class="px-4">
                                @csrf

                                <div class="form-group">
                                    <label for="nama">Nama</label>
                                    <input type="text" class="form-control" name="nama" id="nama" placeholder="{{ $user->nm_emp ?? $user->nama ?? $user->nama_user  }}" readonly>
                                </div>

                                <div class="form-group">
                                    <label for="nip">NIP</label>
                                    <input type="text" class="form-control" name="nip" id="nip" placeholder="{{ $user->nip ?? '-'  }}" readonly>
                                </div>

                                <div class="form-group">
                                    <label for="unit">Unit</label>
                                    <input type="text" class="form-control" name="unit" id="unit" placeholder="{{ $user->status_emp ?? $user->skpd ?? 'Administrator' }}" readonly>
                                </div>

                                <div class="form-group">
                                    <label for="akses">Akses</label>
                                    <input type="akses" class="form-control" name="akses" id="akses" placeholder="{{ $user->idgroup}}" readonly>
                                </div>

                                {{-- <div class="form-group text-center mt-4">
                                    <button type="submit" class="btn btn-success btn-rounded px-4">Simpan</button>
                                </div> --}}
                            </form>
                            <!-- End Form -->
                        </div>
                        <!-- End Profile Body -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


    </main>
    <!-- /.main-wrappper -->
@endsection
