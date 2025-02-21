
@extends('layouts.admin')

@section('main-content')
    <!-- Page Heading -->
    <h1 class="h3 mb-4 text-gray-800">{{ __('Laporan Data Absensi') }}</h1>

    <!-- Informasi Perusahaan -->
    @php
        use Illuminate\Support\Facades\DB;
        $setting = DB::table('setting')->first();
    @endphp

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

    <!-- Tabel Absensi -->
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>No</th>
                <th>Kode Absen</th>
                <th>Nama Karyawan</th>
                <th>Shift</th>
                <th>Tanggal</th>
                <th>Jam Absen</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($absensi as $absen)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $absen->kode_absen }}</td>
                    <td>{{ $absen->user->name }}</td>
                    <td>
                        @if($absen->shift)
                            {{ \Carbon\Carbon::parse($absen->shift->tanggal_shift)->format('d/m/Y') }} - 
                            {{ \Carbon\Carbon::parse($absen->shift->jam_mulai)->format('H:i') }} - 
                            {{ \Carbon\Carbon::parse($absen->shift->jam_selesai)->format('H:i') }}
                        @else
                            Tidak Ada Shift
                        @endif
                    </td>
                    <td>{{ $absen->tanggal_absen->format('d/m/Y') }}</td>
                    <td>{{ $absen->jam_absen }}</td>
                    <td>
                        @if ($absen->status === 'hadir')
                            Hadir
                        @else
                            {{ ucfirst($absen->status) }} - {{ $absen->keterangan }}
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Tombol Print -->
    <div class="text-right mt-4">
        <button class="btn btn-primary" onclick="window.print()">Print</button>
    </div>
@endsection
