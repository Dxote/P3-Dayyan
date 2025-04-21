@extends('layouts.app')

@section('content')

<!-- Animate On Scroll -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/wow/1.1.2/wow.min.js"></script>
<script>
    new WOW().init();
</script>

<div class="container-fluid">

    <!-- Header Outlet -->
    <div class="text-center mb-5">
        <h1 class="display-4 text-primary font-weight-bold wow fadeInDown">{{ $outlet->nama }}</h1>
        <p class="lead wow fadeInUp"><i class="fas fa-map-marker-alt text-danger"></i> {{ $outlet->alamat }}</p>
        <p class="text-muted wow fadeIn"><i class="fas fa-phone-alt text-success"></i> {{ $outlet->no_telp }}</p>
    </div>

    <!-- Layanan Tersedia -->
    <div class="row">
        <div class="col-12 mb-3">
            <h4 class="text-success font-weight-bold wow fadeInLeft">Layanan yang Tersedia</h4>
        </div>

        @forelse ($layanans as $layanan)
            <div class="col-md-4 mb-4">
                <div class="card border-left-success shadow-lg wow zoomIn" data-wow-delay="0.2s">
                    <div class="card-body">
                        <h5 class="card-title font-weight-bold text-dark">{{ $layanan->nama_layanan }}</h5>
                        <p class="card-text">Harga: <span class="text-primary font-weight-bold">Rp {{ number_format($layanan->harga, 0, ',', '.') }}</span></p>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-warning wow fadeIn">
                    Tidak ada layanan tersedia di outlet ini.
                </div>
            </div>
        @endforelse
    </div>

    <!-- Tombol Pesan Sekarang -->
    @if(count($layanans) > 0)
    <div class="text-center mt-5 wow fadeInUp">
        <a href="#" class="btn btn-lg btn-primary px-5 py-3 shadow-lg">
            <i class="fas fa-shopping-cart"></i> Pesan Sekarang
        </a>
    </div>
    @endif

    <!-- Tombol Kembali -->
    <div class="text-center mt-4">
        <a href="{{ route('user.dashboard') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left"></i> Kembali ke Dashboard
        </a>
    </div>

</div>

@endsection
