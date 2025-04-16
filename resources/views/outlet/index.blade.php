@extends('layouts.admin')

@section('main-content')
<h1 class="h3 mb-4 text-gray-800">Data Outlet</h1>

@if (session('message'))
    <div class="alert alert-success">{{ session('message') }}</div>
@endif

<div class="row">
    <!-- TABEL -->
    <div class="col-md-7">
        <div class="card shadow mb-4">
            <div class="card-header">
                <h6 class="m-0 font-weight-bold text-primary">Daftar Outlet</h6>
            </div>
            <div class="card-body">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Outlet</th>
                            <th>Alamat</th>
                            <th>No Telp</th>
                            <th>Layanan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($outlets as $outlet)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $outlet->nama }}</td>
                            <td>{{ $outlet->alamat }}</td>
                            <td>{{ $outlet->no_telp }}</td>
                            <td>
                                @php
                                    $layananIds = explode(',', $outlet->id_layanan);
                                    $namaLayanan = $layanans->whereIn('id_layanan', $layananIds)->pluck('nama_layanan')->toArray();
                                @endphp
                                {{ implode(', ', $namaLayanan) }}
                            </td>

                            <td>
                                <button class="btn btn-sm btn-primary edit-btn" data-id="{{ $outlet->id_outlet }}">Edit</button>
                                <button class="btn btn-sm btn-danger delete-btn" data-id="{{ $outlet->id_outlet }}">Hapus</button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- FORM -->
    <div class="col-md-5">
        <div class="card shadow mb-4">
            <div class="card-header bg-primary text-white">
                <h6 id="form-title" class="m-0">Tambah Outlet</h6>
            </div>
            <div class="card-body">
                <form id="outlet-form" action="{{ route('outlet.store') }}" method="POST">
                    @csrf
                    <input type="hidden" id="form-method" name="_method" value="POST">

                    <div class="form-group">
                        <label for="nama">Nama Outlet</label>
                        <input type="text" name="nama" id="nama" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label for="alamat">Alamat</label>
                        <textarea name="alamat" id="alamat" class="form-control" required></textarea>
                    </div>

                    <div class="form-group">
                        <label for="no_telp">No Telp</label>
                        <input type="text" name="no_telp" id="no_telp" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label>Layanan</label><br>
                        @foreach ($layanans as $layanan)
                            <div class="form-check">
                                <input type="checkbox" name="id_layanan[]" value="{{ $layanan->id_layanan }}" class="form-check-input" id="layanan-{{ $layanan->id_layanan }}">
                                <label class="form-check-label" for="layanan-{{ $layanan->id_layanan }}">{{ $layanan->nama_layanan }}</label>
                            </div>
                        @endforeach
                    </div>


                    <button type="submit" class="btn btn-primary" id="submit-btn">Simpan</button>
                    <button type="button" id="reset-btn" class="btn btn-secondary">Reset</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- AJAX SCRIPT -->
<script>
document.addEventListener("DOMContentLoaded", function () {
    const form = document.getElementById("outlet-form");
    const submitBtn = document.getElementById("submit-btn");
    const resetBtn = document.getElementById("reset-btn");
    const methodInput = document.getElementById("form-method");
    const formTitle = document.getElementById("form-title");

    const namaInput = document.getElementById("nama");
    const alamatInput = document.getElementById("alamat");
    const noTelpInput = document.getElementById("no_telp");
    const layananCheckboxes = document.querySelectorAll('input[name="id_layanan[]"]');

    let currentMode = 'create'; // create | edit | delete

    function resetForm() {
        form.reset();
        form.setAttribute("action", "{{ route('outlet.store') }}");
        methodInput.value = "POST";
        formTitle.textContent = "Tambah Outlet";

        submitBtn.textContent = "Simpan";
        submitBtn.className = "btn btn-primary";
        setInputsDisabled(false);
        currentMode = 'create';
    }

    function setInputsDisabled(disabled) {
        namaInput.disabled = disabled;
        alamatInput.disabled = disabled;
        noTelpInput.disabled = disabled;
        layananCheckboxes.forEach(cb => cb.disabled = disabled);
    }

    form.addEventListener("submit", function (e) {
        e.preventDefault();

        let actionUrl = form.getAttribute("action");
        let method = methodInput.value;

        let options = {
            method: method === "PUT" ? "POST" : (method === "DELETE" ? "DELETE" : "POST"),
            headers: {
                "X-CSRF-TOKEN": document.querySelector('input[name="_token"]').value
            }
        };

        if (method !== "DELETE") {
            options.body = new FormData(form);
        }

        fetch(actionUrl, options)
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                alert(currentMode === 'delete' ? "Data berhasil dihapus!" : "Data berhasil disimpan!");
                location.reload();
            }
        });
    });

    document.addEventListener("click", function (e) {
        // Edit
        if (e.target.classList.contains("edit-btn")) {
            let id = e.target.getAttribute("data-id");

            fetch(`/outlet/${id}/edit`)
                .then(res => res.json())
                .then(data => {
                    const outlet = data.outlet;

                    namaInput.value = outlet.nama;
                    alamatInput.value = outlet.alamat;
                    noTelpInput.value = outlet.no_telp;

                    layananCheckboxes.forEach(cb => {
                        cb.checked = outlet.layanan_array.includes(cb.value);
                    });

                    form.setAttribute("action", `/outlet/${id}`);
                    methodInput.value = "PUT";
                    formTitle.textContent = "Edit Outlet";

                    submitBtn.textContent = "Update";
                    submitBtn.className = "btn btn-success";
                    setInputsDisabled(false);
                    currentMode = 'edit';
                });
        }

        // Delete
        if (e.target.classList.contains("delete-btn")) {
            let id = e.target.getAttribute("data-id");

            fetch(`/outlet/${id}/edit`)
                .then(res => res.json())
                .then(data => {
                    const outlet = data.outlet;

                    namaInput.value = outlet.nama;
                    alamatInput.value = outlet.alamat;
                    noTelpInput.value = outlet.no_telp;

                    layananCheckboxes.forEach(cb => {
                        cb.checked = outlet.layanan_array.includes(cb.value);
                    });

                    form.setAttribute("action", `/outlet/${id}`);
                    methodInput.value = "DELETE";
                    formTitle.textContent = "Hapus Outlet";

                    submitBtn.textContent = "Hapus";
                    submitBtn.className = "btn btn-danger";
                    setInputsDisabled(true);
                    currentMode = 'delete';
                });
        }
    });

    resetBtn.addEventListener("click", resetForm);
});
</script>
@endsection
