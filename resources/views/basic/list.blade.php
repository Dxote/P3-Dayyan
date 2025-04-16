@extends('layouts.admin')

@section('main-content')
<h1 class="h3 mb-4 text-gray-800">Manajemen User</h1>

@if (session('message'))
    <div class="alert alert-success">{{ session('message') }}</div>
@endif

<div class="row">
    <!-- TABEL USER -->
    <div class="col-md-7">
        <div class="card shadow mb-4">
            <div class="card-header bg-primary text-white">
                <h6 class="m-0 font-weight-bold">Daftar User</h6>
            </div>
            <div class="card-body">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Telepon</th>
                            <th>Role</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $user)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $user->name }} {{ $user->last_name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->telepon }}</td>
                            <td>{{ $user->role }}</td>
                            <td>
                                <button class="btn btn-sm btn-primary edit-btn" data-id="{{ $user->id }}">Edit</button>
                                <form action="{{ route('basic.destroy', $user->id) }}" method="POST" class="d-inline">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus?')">Hapus</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                {{ $users->links() }}
            </div>
        </div>
    </div>

    <!-- FORM TAMBAH/EDIT -->
    <div class="col-md-5">
        <div class="card shadow mb-4">
            <div class="card-header bg-primary text-white">
                <h6 id="form-title" class="m-0">Tambah User</h6>
            </div>
            <div class="card-body">
                <form id="user-form" action="{{ route('basic.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" id="form-method" name="_method" value="POST">
                    <input type="hidden" id="edit-id">

                    <div class="form-group">
                        <label for="name">Nama Depan</label>
                        <input type="text" class="form-control" name="name" id="name" required>
                    </div>

                    <div class="form-group">
                        <label for="last_name">Nama Belakang</label>
                        <input type="text" class="form-control" name="last_name" id="last_name">
                    </div>

                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" class="form-control" name="email" id="email" required>
                    </div>

                    <div class="form-group">
                        <label for="telepon">Telepon</label>
                        <input type="text" class="form-control" name="telepon" id="telepon" required>
                    </div>

                    <div class="form-group">
                        <label for="role">Role</label>
                        <select class="form-control" name="role" id="role" required>
                            <option value="user">Pengguna</option>
                            <option value="pegawai">Pegawai</option>
                            <option value="supervisor">Supervisor</option>    
                            <option value="admin">Admin</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" class="form-control" name="password" id="password" placeholder="Isi jika ingin mengubah password">
                    </div>

                    <div class="form-group">
                        <label for="foto">Foto</label>
                        <div id="foto-preview"></div>
                        <input type="file" class="form-control-file" name="foto" id="foto">
                    </div>

                    <button type="submit" class="btn btn-primary">Simpan</button>
                    <button type="button" id="reset-btn" class="btn btn-secondary">Reset</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- SCRIPT -->
<script>
    document.addEventListener("DOMContentLoaded", function () {
    const form = document.getElementById('user-form');
    const resetBtn = document.getElementById('reset-btn');
    const formTitle = document.getElementById('form-title');

    form.addEventListener("submit", function (event) {
    event.preventDefault();

    const formData = new FormData(form);
    formData.set('role', document.getElementById('role').value); // paksa pastikan role masuk
    const method = document.getElementById('form-method').value;
    const editId = document.getElementById('edit-id').value;
    const isEdit = method === 'PUT';
    const actionUrl = isEdit ? `/basic/${editId}` : "{{ route('basic.store') }}";

    if (isEdit) {
        formData.append('_method', 'PUT'); 
    }

    fetch(actionUrl, {
    method: 'POST', // tetap POST karena override pakai _method
    headers: {
        'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
    },
    body: formData
})
.then(async res => {
    const contentType = res.headers.get("content-type");

    if (!res.ok) {
        const errorText = await res.text();
        console.log("Error response:", errorText); // Menambahkan log untuk respons error
        try {
            const data = JSON.parse(errorText);  // Coba parsing JSON jika error
            throw new Error(data.message || "Terjadi kesalahan.");
        } catch {
            throw new Error("Gagal menyimpan (respons tidak valid JSON).");
        }
    }

    const data = await res.json();
    alert(data.message || 'Berhasil!');
    location.reload();
    })
    .catch(err => {
        console.error("Error:", err);
        alert(err.message || "Terjadi kesalahan saat menyimpan.");
    });


    });



    // Tombol Reset
    resetBtn.addEventListener("click", () => {
        form.reset();
        document.getElementById("foto-preview").innerHTML = '';
        document.getElementById("form-method").value = "POST";
        document.getElementById("edit-id").value = "";
        form.action = "{{ route('basic.store') }}";
        formTitle.textContent = "Tambah User";
    });

    // Tombol Edit
    // Event handler untuk tombol edit
document.querySelectorAll(".edit-btn").forEach(btn => {
    btn.addEventListener("click", () => {
        const userId = btn.getAttribute("data-id");
        
        fetch(`/basic/${userId}/edit`)
            .then(res => {
                if (!res.ok) {
                    return res.text().then(text => {
                        throw new Error(text); // Menangani error jika response bukan JSON
                    });
                }

                return res.json();
            })
            fetch(`/basic/${userId}/edit`)
    .then(res => {
        if (!res.ok) {
            return res.text().then(text => {
                throw new Error(text); // Menangani error jika response bukan JSON
            });
        }

        // Pastikan response JSON
        const contentType = res.headers.get("content-type");
        if (!contentType || !contentType.includes("application/json")) {
            throw new Error("Expected JSON, but got " + contentType);
        }

        return res.json();
    })
    .then(data => {
        document.getElementById('name').value = data.name;
        document.getElementById('last_name').value = data.last_name;
        document.getElementById('email').value = data.email;
        document.getElementById('telepon').value = data.telepon;
        document.getElementById('role').value = data.role;
        document.getElementById('password').value = '';
        document.getElementById('edit-id').value = data.id;
        document.getElementById('form-method').value = 'PUT';
        formTitle.textContent = 'Edit User';
        form.action = `/basic/${data.id}`;
        

        if (data.foto) {
            document.getElementById('foto-preview').innerHTML =
                `<img src="/storage/${data.foto}" alt="Foto" style="max-width:200px; margin-bottom:10px;">`;
        } else {
            document.getElementById('foto-preview').innerHTML = '';
        }
    })
    .catch(err => {
        console.error("Error:", err);
        alert(err.message || "Terjadi kesalahan saat memuat data.");
    });

    });
});
});
</script>
@endsection
