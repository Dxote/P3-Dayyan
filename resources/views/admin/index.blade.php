@extends('layouts.admin')

@section('main-content')
<h1 class="h3 mb-4 text-gray-800">Manajemen Admin</h1>

<div class="row">
    <!-- Tabel Admin -->
    <div class="col-md-7">
        <div class="card shadow mb-4">
            <div class="card-header font-weight-bold text-primary">Daftar Admin</div>
            <div class="card-body">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($admins as $a)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $a->name }}</td>
                            <td>{{ $a->email }}</td>
                            <td>
                                <button class="btn btn-sm btn-danger delete-btn" data-id="{{ $a->id_admin }}">Hapus</button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Form Tambah -->
    <div class="col-md-5">
        <div class="card shadow mb-4">
            <div class="card-header bg-primary text-white">
                <h6 id="form-title" class="m-0">Tambah Admin</h6>
            </div>
            <div class="card-body">
                <form id="admin-form" action="{{ route('admin.store') }}" method="POST">
                    @csrf
                    <input type="hidden" id="form-method" name="_method" value="POST">
                    <div class="form-group">
                        <label for="id_user">Pilih User</label>
                        <select name="id_user" id="id_user" class="form-control" required>
                            <option value="">-- Pilih --</option>
                            @foreach ($users as $u)
                                <option value="{{ $u->id }}">{{ $u->name }} ({{ $u->email }})</option>
                            @endforeach
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary" id="submit-btn">Simpan</button>
                    <button type="button" id="reset-btn" class="btn btn-secondary">Reset</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const form = document.getElementById("admin-form");
    const submitBtn = document.getElementById("submit-btn");
    const resetBtn = document.getElementById("reset-btn");
    const methodInput = document.getElementById("form-method");
    const userSelect = document.getElementById("id_user");

    form.addEventListener("submit", function (e) {
        e.preventDefault();

        let actionUrl = form.getAttribute("action");
        let method = methodInput.value;

        let options = {
            method: method === "DELETE" ? "DELETE" : "POST",
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
                    alert("Data berhasil diproses!");
                    location.reload();
                }
            });
    });

    document.addEventListener("click", function (e) {
    if (e.target.classList.contains("delete-btn")) {
        const id = e.target.getAttribute("data-id");

        fetch(`/admin/${id}/edit`)
            .then(res => res.json())
            .then(data => {
                const admin = data.admin;

                // Cek apakah user sudah ada di select, jika tidak tambahkan
                let userExist = [...userSelect.options].some(opt => opt.value == admin.id_user);
                if (!userExist) {
                    const newOption = document.createElement("option");
                    newOption.value = admin.id_user;
                    newOption.textContent = `${admin.user_name} (${admin.user_email})`;
                    newOption.selected = true;
                    userSelect.appendChild(newOption);
                }

                // Set opsi sebagai selected
                let found = false;
                [...userSelect.options].forEach(opt => {
                    if (opt.value == admin.id_user) {
                        opt.selected = true;
                        found = true;
                    }
                });

                if (!found) {
                    console.warn("User tidak ditemukan:", admin.id_user);
                }

                // Set form ke mode hapus
                form.setAttribute("action", `/admin/${id}`);
                methodInput.value = "DELETE";
                submitBtn.textContent = "Hapus";
                submitBtn.className = "btn btn-danger";
                document.getElementById("form-title").textContent = "Hapus Admin";

                userSelect.disabled = true;
                currentMode = "delete";
            })
            .catch(err => console.error("Gagal mengambil data admin:", err));
            }
        });


    resetBtn.addEventListener("click", function () {
        form.reset();
        form.setAttribute("action", "{{ route('admin.store') }}");
        methodInput.value = "POST";
        submitBtn.textContent = "Simpan";
        submitBtn.className = "btn btn-primary";
        userSelect.disabled = false;
    });
});
</script>
@endsection
