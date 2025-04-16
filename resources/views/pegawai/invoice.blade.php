<?php
    use Illuminate\Support\Facades\DB;
    $setting = DB::table('setting')->first();
?>

@extends('layouts.admin')

@section('main-content')
    <!-- Page Heading -->
    <h1 class="h3 mb-4 text-gray-800">{{ $title ?? __('Laporan Data Barang Keluar') }}</h1>

    <!-- Informasi Perusahaan -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <p><strong>Nama Perusahaan:</strong> {{ $setting->nama_perusahaan }}</p>
            <p><strong>Alamat Perusahaan:</strong> {{ $setting->alamat }}</p>
            <p><strong>Website Perusahaan:</strong> {{ $setting->website }}</p>
            <p><strong>Nomor Telepon Perusahaan:</strong> {{ $setting->telepon }}</p>
        </div>
        <div>
            <img src="{{ asset('storage/' . $setting->path_logo) }}" alt="Logo" style="max-width: 150px;">
        </div>
    </div>

    <!-- Notifikasi -->
    @if (session('message'))
        <div class="alert alert-success">
            {{ session('message') }}
        </div>
    @endif

    <!-- Tabel Barang Keluar -->
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>No</th>
                <th>Kode Barang Keluar</th>
                <th>Sparepart</th>
                <th>Jumlah</th>
                <th>Tanggal Keluar</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($barangKeluar as $bk)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $bk->kode_keluar }}</td>
                    <td>{{ $bk->sparepart->nama_sparepart }}</td>
                    <td>{{ $bk->jumlah }}</td>
                    <td>{{ $bk->tanggal_keluar }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Tombol Print -->
    <div class="text-right mt-4">
        <button class="btn btn-primary" onclick="window.print()">Print</button>
    </div>
@endsection

@push('notif')
    @if (session('success'))
        <div class="alert alert-success border-left-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    @if (session('warning'))
        <div class="alert alert-warning border-left-warning alert-dismissible fade show" role="alert">
            {{ session('warning') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    @if (session('status'))
        <div class="alert alert-success border-left-success" role="alert">
            {{ session('status') }}
        </div>
    @endif
@endpush
