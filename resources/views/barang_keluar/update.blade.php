@extends('layouts.admin')


@section('main-content')
    <h1 class="h3 mb-4 text-gray-800">Edit Barang Keluar</h1>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('barang_keluar.update', $keluar->kode_keluar) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label for="kode_keluar">Kode Barang Keluar</label>
                    <input type="text" class="form-control" name="kode_keluar" id="kode_keluar" value="{{ $keluar->kode_keluar }}" readonly>
                </div>

                <div class="form-group">
                    <label for="kode_sparepart">Sparepart</label>
                    <select class="form-control" name="kode_sparepart" id="kode_sparepart" required>
                        @foreach ($spareparts as $sparepart)
                            <option value="{{ $sparepart->kode_sparepart }}" {{ $keluar->kode_sparepart == $sparepart->kode_sparepart ? 'selected' : '' }}>
                                {{ $sparepart->nama_sparepart }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="jumlah">Jumlah</label>
                    <input type="number" class="form-control" name="jumlah" id="jumlah" value="{{ $keluar->jumlah }}" required>
                </div>

                <div class="form-group">
                    <label for="tanggal_keluar">Tanggal Keluar</label>
                    <input type="date" class="form-control" name="tanggal_keluar" id="tanggal_keluar" value="{{ $keluar->tanggal_keluar }}" required>
                </div>

                <button type="submit" class="btn btn-primary">Update</button>
                <a href="{{ route('barang_keluar.index') }}" class="btn btn-secondary">Kembali</a>
            </form>
        </div>
    </div>
@endsection
