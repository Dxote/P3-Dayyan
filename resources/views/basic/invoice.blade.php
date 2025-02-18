<?php
    use Illuminate\Support\Facades\DB;
// Lakukan query ke database untuk mengambil data setting
    $setting = DB::table('setting')->first();
?>

@extends('layouts.admin')

@section('main-content')
    <!-- Page Heading -->
    <h1 class="h3 mb-4 text-gray-800">{{ $title ?? __('Laporan Data Users') }}</h1>

    <!-- Main Content goes here -->

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
                <th>Full Name</th>
                <th>NIS</th>
                <th>Email</th>
                <th>Telepon</th>
                <th>Level</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($users as $user)
                <tr>
                    <td scope="row">{{ $loop->iteration }}</td>
                    <td>{{ $user->full_name }}</td>
                    <td>{{ $user->nis }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->telepon }}</td>
                    <td>{{ $user->level }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{ $users->links() }}
    <div class="text-right mt-4">
        <button class="btn btn-primary" onclick="window.print()">Print</button>
    </div>
    <!-- End of Main Content -->
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
