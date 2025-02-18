@extends('layouts.admin')

@section('main-content')
<h1 class="h3 mb-4 text-gray-800">{{ __('Data Barang Masuk') }}</h1>

@if (session('message'))
    <div class="alert alert-success">{{ session('message') }}</div>
@endif

<div class="row">
    <!-- TABEL DATA -->
    <div class="col-md-7">
        <div class="card shadow mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">Daftar Barang Masuk</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Kode Masuk</th>
                                <th>Sparepart</th>
                                <th>Jumlah</th>
                                <th>Tanggal Masuk</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="barang-masuk-table">
                            @foreach ($barangMasuk as $bm)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $bm->kode_masuk }}</td>
                                <td>{{ $bm->sparepart->nama_sparepart ?? 'Tidak ditemukan' }}</td>
                                <td>{{ $bm->jumlah }}</td>
                                <td>{{ $bm->tanggal_masuk }}</td>
                                <td>
                                    <button class="btn btn-sm btn-primary edit-btn" data-id="{{ $bm->kode_masuk }}">Edit</button>
                                    <button class="btn btn-sm btn-danger delete-btn" data-id="{{ $bm->kode_masuk }}">Hapus</button>
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
                <form id="barang-masuk-form">
                    @csrf
                    <input type="hidden" id="form-method" name="_method" value="POST">
                    <div class="form-group">
                        <label for="kode_masuk">Kode Barang Masuk</label>
                        <input class="form-control" id="kode_masuk" name="kode_masuk" readonly type="text"
                        value="{{ autonumber('barang_masuk', 'kode_masuk', 3, 'MSK') }}">
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
                        <label for="tanggal_masuk">Tanggal Masuk</label>
                        <input type="date" class="form-control" name="tanggal_masuk" id="tanggal_masuk" required>
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
    document.getElementById("barang-masuk-form").addEventListener("submit", function (event) {
    event.preventDefault();

    let formData = new FormData(this);
    let formMethod = document.getElementById("form-method").value;
    let actionUrl = this.getAttribute("action");

    console.log("Mengirim form ke:", actionUrl, "dengan metode:", formMethod);

    fetch(actionUrl, {
        method: formMethod === "PUT" ? "POST" : "POST", // Laravel butuh POST dengan _method PUT
        body: formData,
        headers: { "X-CSRF-TOKEN": document.querySelector('input[name="_token"]').value }
    })
    .then(response => response.json()) // Hapus .catch() di sini agar response bisa dibaca
    .then(data => {
        console.log("Response dari server:", data);

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

        fetch(`/barang_masuk/${id}/edit`)
            .then(response => response.json())
            .then(data => {
                console.log("Data diterima:", data);

                document.getElementById("kode_masuk").value = data.masuk.kode_masuk;
                document.getElementById("kode_sparepart").value = data.masuk.kode_sparepart;
                document.getElementById("jumlah").value = data.masuk.jumlah;
                document.getElementById("tanggal_masuk").value = data.masuk.tanggal_masuk;

                // **Ubah action form agar sesuai dengan kode_masuk**
                let form = document.getElementById("barang-masuk-form");
                form.setAttribute("action", `/barang_masuk/${data.masuk.kode_masuk}`);

                // **Ubah metode form menjadi PUT**
                document.getElementById("form-method").value = "PUT";

                console.log("Form diubah ke mode edit dengan action:", form.action);
            })
            .catch(error => console.error("Error:", error));
    }
});

    // Hapus data via AJAX
    document.addEventListener("click", function (event) {
        if (event.target.classList.contains("delete-btn")) {
            let id = event.target.getAttribute("data-id");
            if (confirm("Apakah Anda yakin ingin menghapus ini?")) {
                fetch(`/barang_masuk/${id}`, {
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
