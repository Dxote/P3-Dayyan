@extends('layouts.admin')


@section('main-content')
    <h1 class="h3 mb-4 text-gray-800">Tambah Barang Keluar</h1>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('barang_keluar.store') }}" method="POST">
                @csrf

                <div class="form-group">
                    <label for="kode_keluar">Kode Barang Keluar</label>
                    <?php $kode_keluar = autonumber('barang_keluar', 'kode_keluar', 3, 'KLR'); ?>
                    <input class="input @error('kode_keluar') is-invalid @enderror form-control" name="kode_keluar" id="kode_keluar" readonly type="text" value="<?= $kode_keluar ?>">
                    @error('kode_keluar')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="kode_sparepart">Sparepart</label>
                    <select class="form-control" name="kode_sparepart" id="kode_sparepart" required>
                        <option value="">Pilih Sparepart</option>
                        @foreach ($spareparts as $sparepart)
                            <option value="{{ $sparepart->kode_sparepart }}">{{ $sparepart->nama_sparepart }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="jumlah">Jumlah</label>
                    <input type="number" class="form-control" name="jumlah" id="jumlah" required>
                </div>

                <div class="form-group">
                    <label for="tanggal_keluar">Tanggal Keluar</label>
                    <input type="date" class="form-control" name="tanggal_keluar" id="tanggal_keluar" required>
                </div>

                <button type="submit" class="btn btn-primary">Simpan</button>
                <a href="{{ route('barang_keluar.index') }}" class="btn btn-secondary">Kembali</a>
            </form>
        </div>
    </div>
@endsection
