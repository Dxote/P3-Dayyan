@extends('layouts.admin')

@section('main-content')
    <!-- Page Heading -->
    <h1 class="h3 mb-4 text-gray-800">{{ $title ?? __('Edit') }}</h1>

    <!-- Main Content goes here -->

    <div class="card">
        <div class="card-body">
        <form action="{{ route('basic.update', $user->id) }}" method="post" enctype="multipart/form-data">
                @csrf
                @method('put')

                <div class="form-group">
                  <label for="name">Name</label>
                  <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" id="name" placeholder="First name" autocomplete="off" value="{{ old('name') ?? $user->name }}">
                  @error('name')
                    <span class="text-danger">{{ $message }}</span>
                  @enderror
                </div>

                <div class="form-group">
                  <label for="last_name">Last Name</label>
                  <input type="text" class="form-control @error('last_name') is-invalid @enderror" name="last_name" id="last_name" placeholder="Last name" autocomplete="off" value="{{ old('last_name') ?? $user->last_name }}">
                  @error('last_name')
                    <span class="text-danger">{{ $message }}</span>
                  @enderror
                </div>

                <div class="form-group">
                  <label for="email">Email</label>
                  <input type="email" class="form-control @error('email') is-invalid @enderror" name="email" id="email" placeholder="Email" autocomplete="off" value="{{ old('email') ?? $user->email }}">
                  @error('email')
                    <span class="text-danger">{{ $message }}</span>
                  @enderror
                </div>

                <div class="form-group">
                  <label for="telepon">Telepon</label>
                  <input type="text" class="form-control @error('telepon') is-invalid @enderror" name="telepon" id="telepon" placeholder="Nomor Telepon" autocomplete="off" value="{{ old('telepon') ?? $user->telepon }}">
                  @error('telepon')
                    <span class="text-danger">{{ $message }}</span>
                  @enderror
                </div>

                <div class="row">
    <div class="col-lg-6">
        <div class="form-group focused">
            <label class="form-control-label" for="level">Level</label>
            <select id="level" class="form-control" name="level" {{ auth()->user()->level == 'petugas' ? 'disabled' : '' }}>
                <option value="admin" {{ old('level', $user->level) == 'admin' ? 'selected' : '' }}>Admin</option>
                <option value="petugas" {{ old('level', $user->level) == 'petugas' ? 'selected' : '' }}>Petugas</option>
                <option value="pengguna" {{ old('level', $user->level) == 'pengguna' ? 'selected' : '' }}>Pengguna</option>
            </select>
        </div>
    </div>
</div>


                <div class="form-group">
                  <label for="password">Password</label>
                  <input type="password" class="form-control @error('password') is-invalid @enderror" name="password" id="password" placeholder="Password" autocomplete="off">
                  @error('password')
                    <span class="text-danger">{{ $message }}</span>
                  @enderror
                </div>

                <div class="form-group">
    <label for="foto">Foto</label>
    @if($user->foto)
        <div>
            <img src="{{ asset('storage/' . $user->foto) }}" alt="User Photo" style="max-width: 200px; margin-bottom: 10px;">
        </div>
        <input type="file" class="form-control-file @error('foto') is-invalid @enderror" name="foto" id="foto">
    @else
        <input type="file" class="form-control-file @error('foto') is-invalid @enderror" name="foto" id="foto" value="{{ old('foto') }}">
    @endif
    @error('foto')
        <span class="text-danger">{{ $message }}</span>
    @enderror
</div>



                <button type="submit" class="btn btn-primary">Save</button>
                <a href="{{ route('basic.index') }}" class="btn btn-default">Back to list</a>

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
