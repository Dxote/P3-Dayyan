<?php
    use Illuminate\Support\Facades\DB;
    $setting = DB::table('setting')->first();
?>

@extends('layouts.admin')

@section('main-content')
    <h1 class="h3 mb-4 text-gray-800">{{ __('Laporan Data Shift') }}</h1>

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

    @if (session('message'))
        <div class="alert alert-success">
            {{ session('message') }}
        </div>
    @endif

    <table class="table table-bordered table-stripped">
        <thead>
            <tr>
                <th>No</th>
                <th>Kode Shift</th>
                <th>Nama Karyawan</th>
                <th>Tanggal Shift</th>
                <th>Jam Mulai</th>
                <th>Jam Selesai</th>
            </tr>
        </thead>
        <tbody>
        @foreach ($shifts as $shift)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $shift->kode_shift }}</td>
                <td>{{ $shift->user->name }}</td>
                <td>{{ $shift->tanggal_shift }}</td>
                <td>{{ $shift->jam_mulai }}</td>
                <td>{{ $shift->jam_selesai }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>

    <div class="text-right mt-4">
        <button class="btn btn-primary" onclick="window.print()">Print</button>
    </div>
@endsection
