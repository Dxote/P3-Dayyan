@extends('layouts.admin')

@section('main-content')
<h1 class="h3 mb-4 text-gray-800">{{ __('Data Barang Keluar') }}</h1>

@if (session('message'))
    <div class="alert alert-success">{{ session('message') }}</div>
@endif

<div class="row">
    <!-- TABEL DATA -->
    <div class="col-md-7">
        <div class="card shadow mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">Daftar Barang Keluar</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Kode Keluar</th>
                                <th>Sparepart</th>
                                <th>Jumlah</th>
                                <th>Tanggal Keluar</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="barang-keluar-table">
                            @foreach ($barangKeluar as $bk)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $bk->kode_keluar }}</td>
                                <td>{{ $bk->sparepart->nama_sparepart ?? 'Tidak ditemukan' }}</td>
                                <td>{{ $bk->jumlah }}</td>
                                <td>{{ $bk->tanggal_keluar }}</td>
                                <td>
                                    <button class="btn btn-sm btn-primary edit-btn" data-id="{{ $bk->kode_keluar }}">Edit</button>
                                    <button class="btn btn-sm btn-danger delete-btn" data-id="{{ $bk->kode_keluar }}">Hapus</button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- FORM TAMBAH -->
    <div class="col-md-5">
        <div class="card shadow mb-4">
            <div class="card-header bg-primary text-white">
                <h6 id="form-title" class="m-0">Tambah Data</h6>
            </div>
            <div class="card-body">
                <form id="barang-keluar-form" action="{{ route('barang_keluar.store') }}" method="POST">
                    @csrf
                    <input type="hidden" id="form-method" name="_method" value="POST">
                    <div class="form-group">
                        <label for="kode_keluar">Kode Barang Keluar</label>
                        <input class="form-control" id="kode_keluar" name="kode_keluar" readonly type="text"
                        value="{{ autonumber('barang_keluar', 'kode_keluar', 3, 'KLR') }}">
                    </div>

                    <div class="form-group">
                        <label for="kode_sparepart">Sparepart</label>
                        <select class="form-control" name="kode_sparepart" id="kode_sparepart" required>
                            <option value="">Pilih Sparepart</option>
                            @foreach ($sparepart as $spareparts)
                                <option value="{{ $spareparts->kode_sparepart }}">{{ $spareparts->nama_sparepart }}</option>
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
                    <button type="button" id="reset-btn" class="btn btn-secondary">Reset</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- SCRIPT AJAX -->
<script>
document.addEventListener("DOMContentLoaded", function () {
    document.getElementById("barang-keluar-form").addEventListener("submit", function (event) {
        event.preventDefault();

        let formData = new FormData(this);
        let formMethod = document.getElementById("form-method").value;
        let actionUrl = this.getAttribute("action");

        fetch(actionUrl, {
            method: formMethod === "PUT" ? "POST" : "POST",
            body: formData,
            headers: { "X-CSRF-TOKEN": document.querySelector('input[name="_token"]').value }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert("Data berhasil disimpan!");
                location.reload();
            } else {
                alert("Gagal menyimpan: " + data.message);
            }
        })
        .catch(error => console.error("Fetch Error:", error));
    });

    document.addEventListener("click", function (event) {
        if (event.target.classList.contains("edit-btn")) {
            let id = event.target.getAttribute("data-id");

            fetch(`/barang_keluar/${id}/edit`)
                .then(response => response.json())
                .then(data => {
                    document.getElementById("kode_keluar").value = data.keluar.kode_keluar;
                    document.getElementById("kode_sparepart").value = data.keluar.kode_sparepart;
                    document.getElementById("jumlah").value = data.keluar.jumlah;
                    document.getElementById("tanggal_keluar").value = data.keluar.tanggal_keluar;

                    let form = document.getElementById("barang-keluar-form");
                    form.setAttribute("action", `/barang_keluar/${data.keluar.kode_keluar}`);

                    document.getElementById("form-method").value = "PUT";
                })
                .catch(error => console.error("Error:", error));
        }
    });

    document.addEventListener("click", function (event) {
        if (event.target.classList.contains("delete-btn")) {
            let id = event.target.getAttribute("data-id");
            if (confirm("Apakah Anda yakin ingin menghapus ini?")) {
                fetch(`/barang_keluar/${id}`, {
                    method: "DELETE",
                    headers: {
                        "X-CSRF-TOKEN": document.querySelector('input[name="_token"]').value
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        event.target.closest("tr").remove();
                        alert("Data berhasil dihapus!");
                    } else {
                        alert("Terjadi kesalahan saat menghapus data!");
                    }
                })
                .catch(error => console.error("Error:", error));
            }
        }
    });
});
</script>

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
