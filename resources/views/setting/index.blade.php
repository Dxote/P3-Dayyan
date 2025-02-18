@extends('layouts.admin')

@section('main-content')
    <!-- Page Heading -->
    <h1 class="h3 mb-4 text-gray-800">{{ $title ?? __('Setting') }}</h1>

    <!-- Main Content goes here -->

    <div class="card">
        <div class="card-body">
        <form action="{{ url('setting/' . $setting->id_setting) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('put')

                <div class="form-group">
                  <label for="nama_sekolah">Nama Perushaan</label>
                  <input type="text" class="form-control @error('nama_perusahaan') is-invalid @enderror" name="nama_perusahaan" id="nama_perusahaan" placeholder="Nama Perusahaan" autocomplete="off" value="{{ old('nama_perusahaan') ?? $setting->nama_perusahaan }}">
                  @error('nama_perusahaan')
                    <span class="text-danger">{{ $message }}</span>
                  @enderror
                </div>

                <div class="form-group">
                  <label for="alamat">Alamat</label>
                  <input type="text" class="form-control @error('alamat') is-invalid @enderror" name="alamat" id="alamat" placeholder="Input Alamat" autocomplete="off" value="{{ old('alamat') ?? $setting->alamat }}">
                  @error('alamat')
                    <span class="text-danger">{{ $message }}</span>
                  @enderror
                </div>

                <div class="form-group">
                  <label for="email">Email</label>
                  <input type="email" class="form-control @error('email') is-invalid @enderror" name="email" id="email" placeholder="Input Email" autocomplete="off" value="{{ old('email') ?? $setting->email }}">
                  @error('email')
                    <span class="text-danger">{{ $message }}</span>
                  @enderror
                </div>

                <div class="form-group">
                  <label for="website">Website</label>
                  <input type="text" class="form-control @error('website') is-invalid @enderror" name="website" id="website" placeholder="Input Website" autocomplete="off" value="{{ old('website') ?? $setting->website }}">
                  @error('website')
                    <span class="text-danger">{{ $message }}</span>
                  @enderror
                </div>

                <div class="form-group">
                  <label for="kodepos">Kode Pos</label>
                  <input type="text" class="form-control @error('kodepos') is-invalid @enderror" name="kodepos" id="kodepos" placeholder="Input Kodepos" autocomplete="off" value="{{ old('kodepos') ?? $setting->kodepos }}">
                  @error('kodepos')
                    <span class="text-danger">{{ $message }}</span>
                  @enderror
                </div>

                <div class="form-group">
                  <label for="telepon">Telepon</label>
                  <input type="number" class="form-control @error('telepon') is-invalid @enderror" name="telepon" id="telepon" placeholder="Input Telepon" autocomplete="off" value="{{ old('telepon') ?? $setting->telepon }}">
                  @error('telepon')
                    <span class="text-danger">{{ $message }}</span>
                  @enderror
                </div>

                <div class="form-group">
        <label for="current_logo">Current Logo</label>
        @if ($setting->path_logo)
            <img src="{{ asset('storage/' . $setting->path_logo) }}" alt="Current Logo" style="max-width: 200px;">
        @else
            <p>No logo available</p>
        @endif
    </div>

                <div class="form-group">
        <label for="path_logo">Logo</label>
        <input type="file" class="form-control-file" name="path_logo" id="path_logo">
        @error('path_logo')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>

                <button type="submit" class="btn btn-primary">Save</button>

            </form>
        </div>
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
