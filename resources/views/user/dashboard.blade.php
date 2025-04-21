@extends('layouts.app')

@section('content')

<!-- Animate On Scroll -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/wow/1.1.2/wow.min.js"></script>
<script>
    new WOW().init();
</script>

<div class="container-fluid">

    <!-- Heading -->
    <div class="text-center mb-5">
        <h1 class="h3 text-gray-800 wow fadeInDown">Selamat Datang di {{ $setting->nama_perusahaan }}</h1>
        <p class="lead wow fadeInUp">Solusi laundry cepat, bersih, dan terpercaya.</p>
    </div>

    <!-- Daftar Outlet -->
    <div class="row">
        <div class="col-12 mb-4">
            <h4 class="text-primary font-weight-bold wow fadeInLeft">Pilih Outlet Terdekat</h4>
        </div>

        @foreach ($outlets as $outlet)
            <div class="col-md-4 mb-4">
                <div class="card shadow-lg border-left-primary wow fadeInUp" data-wow-delay="0.2s">
                    <div class="card-body">
                        <h5 class="card-title font-weight-bold text-dark">{{ $outlet->nama }}</h5>
                        <p><i class="fas fa-map-marker-alt text-danger"></i> {{ $outlet->alamat }}</p>
                        <p><i class="fas fa-phone text-success"></i> {{ $outlet->no_telp }}</p>
                        <a href="{{ route('outlet.show', $outlet->id_outlet) }}" class="btn btn-primary mt-3">
                            <i class="fas fa-eye"></i> Lihat Layanan
                        </a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Tentang Kami -->
    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow-lg mb-4 border-left-success wow fadeIn" data-wow-delay="0.5s">
                <div class="card-body">
                    <h5 class="font-weight-bold text-success">Tentang Kami</h5>
                    <p>
                        {{ $setting->deskripsi ?? 'Kami adalah penyedia layanan laundry profesional dengan jaringan outlet di berbagai tempat. Layanan cepat, bersih, dan ramah pelanggan adalah prioritas kami.' }}
                    </p>
                </div>
            </div>
        </div>
    </div>

</div>

<!-- Footer -->
<footer class="bg-primary text-white text-center py-3 mt-5">
    <p>&copy; {{ date('Y') }} {{ $setting->nama_perusahaan }}. All rights reserved.</p>
</footer>

@endsection
