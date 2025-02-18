@extends('layouts.admin')

@section('main-content')
<h1 class="h3 mb-4 text-gray-800">{{ __('Data Sparepart') }}</h1>

@if (session('message'))
    <div class="alert alert-success">{{ session('message') }}</div>
@endif

<div class="row">
    <!-- TABEL DATA -->
    <div class="col-md-7">
        <div class="card shadow mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">Daftar Sparepart</h6>
                <div>
                    <a href="{{ route('sparepart.invoice') }}" class="btn btn-sm btn-info">Invoice</a>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Kode</th>
                                <th>Nama</th>
                                <th>Stok</th>
                                <th>Harga</th>
                                <th>Satuan</th>
                                <th>Brand</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($sparepart as $item)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $item->kode_sparepart }}</td>
                                <td>{{ $item->nama_sparepart }}</td>
                                <td>{{ $item->stok }}</td>
                                <td>{{ number_format($item->harga, 0, ',', '.') }}</td>
                                <td>{{ $item->jumlah_satuan }} {{ $item->satuan->nama_satuan }}</td>
                                <td>{{ $item->brand->brand }}</td>
                                <td>
                                    <button class="btn btn-sm btn-primary edit-btn" data-id="{{ $item->kode_sparepart }}">Edit</button>
                                    <form action="{{ route('sparepart.destroy', $item->kode_sparepart) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus?')">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- FORM TAMBAH & EDIT -->
    <div class="col-md-5">
        <div class="card shadow mb-4">
            <div class="card-header bg-primary text-white">
                <h6 id="form-title" class="m-0">Tambah Data</h6>
            </div>
            <div class="card-body">
                <form id="sparepart-form" method="POST" action="{{ route('sparepart.store') }}">
                    @csrf
                    <input type="hidden" id="form-method" name="_method" value="POST">

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="kode_sparepart">Kode Sparepart</label>
                                <input type="text" class="form-control" id="kode_sparepart" name="kode_sparepart"
                                    value="{{ autonumber('sparepart', 'kode_sparepart', 3, 'SPR') }}" readonly>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="nama_sparepart">Nama Sparepart</label>
                                <input type="text" class="form-control" id="nama_sparepart" name="nama_sparepart" required>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="stok">Stok</label>
                        <input type="number" class="form-control" id="stok" name="stok" required>
                    </div>

                    <div class="form-group">
                        <label for="harga">Harga</label>
                        <input type="number" class="form-control" id="harga" name="harga" required>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="jumlah_satuan">Jumlah Satuan</label>
                                <input type="number" class="form-control" id="jumlah_satuan" name="jumlah_satuan" required>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="kode_satuan">Satuan</label>
                                <select class="form-control" id="kode_satuan" name="kode_satuan">
                                    <option value="" selected>Pilih Satuan</option>
                                    @foreach ($satuan as $s)
                                        <option value="{{ $s->kode_satuan }}">{{ $s->nama_satuan }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="kode_brand">Brand</label>
                        <select class="form-control" id="kode_brand" name="kode_brand">
                            <option value="" selected>Pilih Brand</option>
                            @foreach ($brand as $b)
                                <option value="{{ $b->kode_brand }}">{{ $b->brand }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="text-right">
                        <button type="submit" class="btn btn-primary">Simpan</button>
                        <button type="button" id="reset-btn" class="btn btn-secondary">Reset</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- SCRIPT AJAX UNTUK EDIT DAN RESET FORM -->
<script>
document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll('.edit-btn').forEach(button => {
        button.addEventListener('click', function () {
            let kode_sparepart = this.getAttribute('data-id');

            fetch(`/sparepart/${kode_sparepart}/edit`)
                .then(response => {
                    if (!response.ok) throw new Error("Gagal mengambil data");
                    return response.json();
                })
                .then(data => {
                    const sparepart = data.sparepart;
                    document.getElementById('sparepart-form').action = `/sparepart/${sparepart.kode_sparepart}`;
                    document.getElementById('form-method').value = "PUT";
                    document.getElementById('kode_sparepart').value = sparepart.kode_sparepart;
                    document.getElementById('nama_sparepart').value = sparepart.nama_sparepart;
                    document.getElementById('stok').value = sparepart.stok;
                    document.getElementById('harga').value = sparepart.harga;
                    document.getElementById('jumlah_satuan').value = sparepart.jumlah_satuan;
                    document.getElementById('kode_satuan').value = sparepart.kode_satuan;
                    document.getElementById('kode_brand').value = sparepart.kode_brand;
                    document.getElementById('form-title').textContent = "Edit Data";
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert("Terjadi kesalahan saat mengambil data.");
                });
        });
    });
});
</script>

@endsection
