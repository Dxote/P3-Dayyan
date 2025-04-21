@extends('layouts.app')

@section('content')

<div class="container-fluid">
    <div class="text-center mb-5">
        <h1 class="h3 text-gray-800 wow fadeInDown">{{ $outlet->nama }}</h1>
        <p class="lead wow fadeInUp"><i class="fas fa-map-marker-alt text-primary"></i> {{ $outlet->alamat }}</p>
        <p class="text-muted"><i class="fas fa-phone-alt text-success"></i> {{ $outlet->no_telp }}</p>
    </div>

    <!-- Daftar Layanan -->
    <div class="row justify-content-center">
        <div class="col-12 mb-4 text-center">
            <h4 class="text-primary font-weight-bold wow fadeInLeft">
                <i class="fas fa-concierge-bell"></i> Layanan Tersedia
            </h4>
            <p class="text-muted">Layanan yang tersedia di outlet ini:</p>
        </div>

        @forelse ($layanans as $layanan)
            @php
                $hargaAwal = $layanan->harga;
                $diskonPersen = ($totalDiskonPersen / 100) * $hargaAwal;
                $hargaSetelahDiskon = $hargaAwal - $diskonPersen - $totalDiskonNominal;
                $hargaSetelahDiskon = max(0, $hargaSetelahDiskon); // Hindari negatif
            @endphp

            <div class="col-md-6 col-lg-4 mb-4 d-flex align-items-stretch">
                <div class="card shadow-lg border-0 wow fadeInUp" data-wow-delay="0.2s">
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title font-weight-bold text-dark mb-3">
                            <i class="fas fa-tshirt text-info"></i> {{ $layanan->nama }}
                        </h5>

                        <p class="card-text text-muted mb-2">
                            <span class="text-secondary">Harga Asli:</span> 
                            <del>Rp{{ number_format($hargaAwal, 0, ',', '.') }}</del><br>
                            <span class="text-warning">Diskon: {{ $totalDiskonPersen }}% + Rp{{ number_format($totalDiskonNominal, 0, ',', '.') }}</span>
                        </p>

                        <h4 class="text-success font-weight-bold mt-auto">
                            Rp{{ number_format($hargaSetelahDiskon, 0, ',', '.') }}
                        </h4>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center">
                <div class="alert alert-info wow fadeIn">
                    Belum ada layanan untuk outlet ini.
                </div>
            </div>
        @endforelse
    </div>
</div>

@endsection
