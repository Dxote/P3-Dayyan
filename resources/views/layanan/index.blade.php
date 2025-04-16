@extends('layouts.admin')

@section('main-content')
<h1 class="h3 mb-4 text-gray-800">Data Layanan Laundry</h1>

<div class="row">
    <div class="col-md-7">
        <div class="card shadow mb-4">
            <div class="card-header font-weight-bold text-primary">Daftar Layanan</div>
            <div class="card-body">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>Jenis</th>
                            <th>Harga</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="layanan-table">
                        @foreach ($layanan as $l)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $l->nama_layanan }}</td>
                            <td>{{ $l->jenis }}</td>
                            <td>{{ number_format($l->harga, 0, ',', '.') }}</td>
                            <td>
                                <button class="btn btn-sm btn-primary edit-btn" data-id="{{ $l->id_layanan }}">Edit</button>
                                <button class="btn btn-sm btn-danger delete-btn" data-id="{{ $l->id_layanan }}">Hapus</button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-md-5">
        <div class="card shadow mb-4">
            <div class="card-header bg-primary text-white">
                <h6 id="form-title" class="m-0">Tambah Layanan</h6>
            </div>
            <div class="card-body">
            <form id="layanan-form" action="{{ route('layanan.store') }}" method="POST">
                @csrf
                <input type="hidden" id="form-method" name="_method" value="POST">

                <div class="form-group">
                    <label for="nama_layanan">Nama Layanan</label>
                    <input type="text" class="form-control" id="nama_layanan" name="nama_layanan" required>
                </div>

                <div class="form-group">
                    <label for="jenis">Jenis</label>
                    <input type="text" class="form-control" id="jenis" name="jenis" required>
                </div>

                <div class="form-group">
                    <label for="harga">Harga</label>
                    <input type="number" class="form-control" id="harga" name="harga" required>
                </div>

                <button type="submit" id="submit-btn" class="btn btn-primary">Simpan</button>
                <button type="button" id="reset-btn" class="btn btn-secondary">Reset</button>
            </form>

            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const form = document.getElementById("layanan-form");
    const submitBtn = document.getElementById("submit-btn");
    const resetBtn = document.getElementById("reset-btn");
    const methodInput = document.getElementById("form-method");
    const formTitle = document.getElementById("form-title");

    const namaInput = document.getElementById("nama_layanan");
    const jenisInput = document.getElementById("jenis");
    const hargaInput = document.getElementById("harga");

    let currentMode = 'create'; // 'create' | 'edit' | 'delete'

    // 🔄 Reset form ke mode "tambah"
    function resetForm() {
        form.reset();
        form.setAttribute("action", "{{ route('layanan.store') }}");
        methodInput.value = "POST";
        formTitle.textContent = "Tambah Layanan";

        submitBtn.textContent = "Simpan";
        submitBtn.className = "btn btn-primary";
        setInputsDisabled(false);
        currentMode = 'create';
    }

    // 🔒 Ubah semua input jadi readonly/disabled
    function setInputsDisabled(isDisabled) {
        namaInput.disabled = isDisabled;
        jenisInput.disabled = isDisabled;
        hargaInput.disabled = isDisabled;
    }

    // 📝 Handle SUBMIT (baik simpan, update, maupun hapus)
    form.addEventListener("submit", function (event) {
        event.preventDefault();

        let actionUrl = form.getAttribute("action");
        let method = methodInput.value;

        let options = {
            method: method === "PUT" ? "POST" : (method === "DELETE" ? "DELETE" : "POST"),
            headers: {
                "X-CSRF-TOKEN": document.querySelector('input[name="_token"]').value
            }
        };

        if (method !== "DELETE") {
            const formData = new FormData(form);
            options.body = formData;
        }

        fetch(actionUrl, options)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(currentMode === 'delete' ? "Data berhasil dihapus!" : "Data berhasil disimpan!");
                location.reload();
            } else {
                alert("Terjadi kesalahan: " + (data.message || "Gagal."));
            }
        });
    });

    // Edit mode
    document.addEventListener("click", function (event) {
        if (event.target.classList.contains("edit-btn")) {
            let id = event.target.getAttribute("data-id");

            fetch(`/layanan/${id}/edit`)
                .then(response => response.json())
                .then(data => {
                    const layanan = data.layanan;

                    namaInput.value = layanan.nama_layanan;
                    jenisInput.value = layanan.jenis;
                    hargaInput.value = layanan.harga;

                    form.setAttribute("action", `/layanan/${id}`);
                    methodInput.value = "PUT";
                    formTitle.textContent = "Edit Layanan";

                    submitBtn.textContent = "Update";
                    submitBtn.className = "btn btn-success";
                    setInputsDisabled(false);

                    currentMode = 'edit';
                });
        }

        // Delete mode
        if (event.target.classList.contains("delete-btn")) {
            let id = event.target.getAttribute("data-id");

            fetch(`/layanan/${id}/edit`)
                .then(response => response.json())
                .then(data => {
                    const layanan = data.layanan;

                    namaInput.value = layanan.nama_layanan;
                    jenisInput.value = layanan.jenis;
                    hargaInput.value = layanan.harga;

                    form.setAttribute("action", `/layanan/${id}`);
                    methodInput.value = "DELETE";
                    formTitle.textContent = "Hapus Layanan";

                    submitBtn.textContent = "Hapus";
                    submitBtn.className = "btn btn-danger";
                    setInputsDisabled(true);

                    currentMode = 'delete';
                });
        }
    });

    // Tombol reset
    resetBtn.addEventListener("click", resetForm);
});
</script>
@endsection
