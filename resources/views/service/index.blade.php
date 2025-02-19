@extends('layouts.admin')

@section('main-content')
<h1 class="h3 mb-4 text-gray-800">{{ __('Data Service') }}</h1>

@if (session('message'))
    <div class="alert alert-success">{{ session('message') }}</div>
@endif

<div class="row">
    <!-- TABEL DATA -->
    <div class="col-md-7">
        <div class="card shadow mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">Daftar Service</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Plat Nomor</th>
                                <th>Motor</th>
                                <th>Brand</th>
                                <th>Masalah</th>
                                <th>Petugas</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach ($service as $item)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $item->plat_nomor }}</td>
                                <td>{{ $item->nama_motor }}</td>
                                <td>{{ $item->brand->brand }}</td>
                                <td>{{ $item->deskripsi_masalah }}</td>
                                <td>{{ $item->petugas->name }}</td>
                                <td>
                                    <button class="btn btn-sm btn-primary edit-btn" data-id="{{ $item->kode_service }}">Edit</button>
                                    <form action="{{ route('service.destroy', $item->kode_service) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-primary edit-btn" data-id="{{ $item->kode_service }}">Edit</button>
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
                <h6 id="form-title" class="m-0">Tambah Service</h6>
            </div>
            <div class="card-body">
                <form id="service-form" method="POST" action="{{ route('service.store') }}">
                    @csrf
                    <input type="hidden" id="form-method" name="_method" value="POST">

                    <div class="form-group">
                        <label for="kode_service">Kode Service</label>
                        <input type="text" class="form-control" id="kode_service" name="kode_service" value="{{ autonumber('service', 'kode_service', 3, 'SVC') }}" readonly>
                    </div>

                    <div class="form-group">
                        <label for="plat_nomor">Plat Nomor</label>
                        <input type="text" class="form-control" id="plat_nomor" name="plat_nomor" required>
                    </div>

                    <div class="form-group">
                        <label for="nama_motor">Nama Motor</label>
                        <input type="text" class="form-control" id="nama_motor" name="nama_motor" required>
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

                    <div class="form-group">
                        <label for="deskripsi_masalah">Deskripsi Masalah</label>
                        <textarea class="form-control" id="deskripsi_masalah" name="deskripsi_masalah" required></textarea>
                    </div>

                    <div class="form-group">
                        <label>Sparepart</label>
                        @foreach ($spareparts as $s)
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="spareparts[]" value="{{ $s->kode_sparepart }}">
                                <label class="form-check-label">{{ $s->nama_sparepart }} - Rp{{ number_format($s->harga, 0, ',', '.') }}</label>
                            </div>
                        @endforeach
                    </div>

                    <div class="form-group">
                        <label>Alat</label>
                        @foreach ($alat as $a)
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="alat[]" value="{{ $a->kode_alat }}">
                                <label class="form-check-label">{{ $a->nama_alat }}</label>
                            </div>
                        @endforeach
                    </div>

                    <div class="form-group">
                        <label for="kode_user">Nama User</label>
                        <select class="form-control" id="kode_user" name="kode_user">
                            <option value="" selected>Pilih User</option>
                            @foreach ($users as $u)
                                <option value="{{ $u->id }}">{{ $u->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="kode_petugas">Nama Petugas</label>
                        <select class="form-control" id="kode_petugas" name="kode_petugas">
                            <option value="" selected>Pilih Petugas</option>
                            @foreach ($petugas as $p)
                                <option value="{{ $p->id }}">{{ $p->name }}</option>
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
            let kode_service = this.getAttribute('data-id');

            fetch(`/service/${kode_service}/edit`)
                .then(response => {
                    if (!response.ok) throw new Error("Gagal mengambil data");
                    return response.json();
                })
                .then(data => {
                    const service = data;
                    document.getElementById('service-form').action = `/service/${service.kode_service}`;
                    document.getElementById('form-method').value = "PUT";
                    document.getElementById('plat_nomor').value = service.plat_nomor;
                    document.getElementById('nama_motor').value = service.nama_motor;
                    document.getElementById('kode_brand').value = service.kode_brand;
                    document.getElementById('deskripsi_masalah').value = service.deskripsi_masalah;
                    document.getElementById('kode_user').value = service.kode_user;
                    document.getElementById('kode_petugas').value = service.kode_petugas;
                    document.getElementById('form-title').textContent = "Edit Service";

                    // **Centang sparepart hanya jika ada datanya**
                    document.querySelectorAll('input[name="spareparts[]"]').forEach(input => {
                        input.checked = service.spareparts?.some(s => s.kode_sparepart == input.value) ?? false;
                    });

                    // **Centang alat hanya jika ada datanya**
                    document.querySelectorAll('input[name="alat[]"]').forEach(input => {
                        input.checked = service.alat?.some(a => a.kode_alat == input.value) ?? false;
                    });
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert("Terjadi kesalahan saat mengambil data.");
                });
        });
    });

    // Reset form saat klik tombol reset
    document.getElementById('reset-btn').addEventListener('click', function () {
        document.getElementById('service-form').reset();
        document.getElementById('form-title').textContent = "Tambah Service";
        document.getElementById('form-method').value = "POST";
        document.getElementById('service-form').action = "{{ route('service.store') }}";
    });
});
</script>

@endsection
