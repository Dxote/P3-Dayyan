@extends('layouts.admin')

@section('main-content')
<h1 class="h3 mb-4 text-gray-800">Data Pegawai</h1>

<div class="row">
    <!-- TABEL -->
    <div class="col-md-7">
        <div class="card shadow mb-4">
            <div class="card-header font-weight-bold text-primary">Daftar Pegawai</div>
            <div class="card-body">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Outlet</th>
                            <th>Jabatan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($pegawais as $p)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $p->user->name ?? '-' }} {{ $p->user->last_name ?? '' }}</td>
                            <td>{{ $p->user->email ?? '-' }}</td>
                            <td>{{ $p->outlet->nama ?? '-' }}</td>
                            <td>{{ $p->jabatan }}</td>
                            <td>
                                <button class="btn btn-sm btn-primary edit-btn" data-id="{{ $p->id_pegawai }}">Edit</button>
                                <button class="btn btn-sm btn-danger delete-btn" data-id="{{ $p->id_pegawai }}">Hapus</button>
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
                <h6 id="form-title" class="m-0">Tambah Pegawai</h6>
            </div>
            <div class="card-body">
                <form id="pegawai-form" action="{{ route('pegawai.store') }}" method="POST">
                    @csrf
                    <input type="hidden" id="form-method" name="_method" value="POST">

                    <div class="form-group">
                        <label for="id_user">Pilih User</label>
                        <select name="id_user" id="id_user" class="form-control" required>
                            <option value="">Pilih User</option>
                                    @foreach ($users as $user)
                                <option value="{{ $user->id }}" @if(isset($pegawai) && $pegawai->id_user == $user->id) selected @endif>
                                    {{ $user->name }} {{ $user->last_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="id_outlet">Pilih Outlet</label>
                        <select name="id_outlet" id="id_outlet" class="form-control" required>
                            <option value="">Pilih Outlet</option>
                            @foreach ($outlets as $outlet)
                                <option value="{{ $outlet->id_outlet }}" @if(isset($pegawai) && $pegawai->id_outlet == $outlet->id_outlet) selected @endif>
                                    {{ $outlet->nama }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="jabatan">Jabatan</label>
                        <input type="text" name="jabatan" id="jabatan" class="form-control" value="{{ old('jabatan', isset($pegawai) ? $pegawai->jabatan : '') }}" required>
                    </div>

                    <button type="submit" class="btn btn-primary" id="submit-btn">Simpan</button>
                    <button type="button" class="btn btn-secondary" id="reset-btn">Reset</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const form = document.getElementById("pegawai-form");
    const methodInput = document.getElementById("form-method");
    const submitBtn = document.getElementById("submit-btn");
    const formTitle = document.getElementById("form-title");
    const idUserSelect = document.getElementById("id_user");
    const idOutletSelect = document.getElementById("id_outlet");
    const jabatanInput = document.getElementById("jabatan");

    let currentMode = 'create';

    function resetForm() {
        form.reset();
        form.setAttribute("action", "{{ route('pegawai.store') }}");
        methodInput.value = "POST";
        submitBtn.textContent = "Simpan";
        submitBtn.className = "btn btn-primary";
        formTitle.textContent = "Tambah Pegawai";
        idUserSelect.disabled = false;
        idOutletSelect.disabled = false;
        jabatanInput.disabled = false;
        currentMode = 'create';
    }

    form.addEventListener("submit", function (e) {
    e.preventDefault();

    let actionUrl = form.getAttribute("action");
    let method = methodInput.value;
    let headers = {
        "X-CSRF-TOKEN": document.querySelector('input[name="_token"]').value,
        "Accept": "application/json"
    };

    if (method === "DELETE") {
        fetch(actionUrl, {
            method: "POST",
            headers: {
                ...headers,
                "Content-Type": "application/json"
            },
            body: JSON.stringify({ _method: "DELETE" })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                alert('Data berhasil dihapus!');
                location.reload();
            }
        });
    } else {
        const formData = new FormData(form);
        if (method === "PUT") {
            formData.append('_method', 'PUT');
        }

        fetch(actionUrl, {
            method: "POST",
            headers,
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                alert('Data berhasil disimpan!');
                location.reload();
            } else {
                alert('Gagal menyimpan data.');
                console.log(data);
            }
        });
    }
});


    document.addEventListener("click", function (e) {
        if (e.target.classList.contains("edit-btn")) {
        currentMode = 'edit';
        const id = e.target.getAttribute("data-id");

        fetch(`/pegawai/${id}/edit`)
            .then(res => res.json())
            .then(data => {
                const pegawai = data.pegawai;
                const user = data.user;
                
                let userExist = [...idUserSelect.options].some(opt => opt.value == pegawai.id_user);
                if (!userExist) {
                    const newOption = document.createElement("option");
                    newOption.value = pegawai.id_user;
                    newOption.textContent = `${pegawai.user?.name || 'User'} ${pegawai.user?.last_name || ''}`;
                    newOption.selected = true;
                    idUserSelect.appendChild(newOption);
                }

                idUserSelect.value = pegawai.id_user;
                idOutletSelect.value = pegawai.id_outlet;
                jabatanInput.value = pegawai.jabatan;

                // Debug tambahan (opsional)
                console.log("User name:", user?.name);
                console.log("User last_name:", user?.last_name);
                console.log("Selected id_user:", pegawai.id_user);

                form.setAttribute("action", `/pegawai/${id}`);
                methodInput.value = currentMode === 'delete' ? "DELETE" : "PUT";
                submitBtn.textContent = currentMode === 'delete' ? "Hapus" : "Update";
                submitBtn.className = currentMode === 'delete' ? "btn btn-danger" : "btn btn-success";
                formTitle.textContent = currentMode === 'delete' ? "Hapus Pegawai" : "Edit Pegawai";

                const disableFields = currentMode === 'delete';
                idUserSelect.disabled = disableFields;
                idOutletSelect.disabled = disableFields;
                jabatanInput.disabled = disableFields;
            });

        }

        if (e.target.classList.contains("delete-btn")) {
        const id = e.target.getAttribute("data-id");

        fetch(`/pegawai/${id}/edit`)
        .then(res => res.json())
        .then(data => {
            const pegawai = data.pegawai;
            let userExist = [...idUserSelect.options].some(opt => opt.value == pegawai.id_user);
                if (!userExist) {
                    const newOption = document.createElement("option");
                    newOption.value = pegawai.id_user;
                    newOption.textContent = `${pegawai.user?.name || 'User'} ${pegawai.user?.last_name || ''}`;
                    newOption.selected = true;
                    idUserSelect.appendChild(newOption);
                }

            // Set value dengan fallback manual
            let found = false;
            [...idUserSelect.options].forEach(opt => {
                if (opt.value == pegawai.id_user) {
                    opt.selected = true;
                    found = true;
                }
                console.log("Value dari pegawai.id_user:", pegawai.id_user);
            console.log("Semua opsi id_user:");
            [...idUserSelect.options].forEach(opt => {
                console.log(opt.value, opt.textContent);
            });
            });

            if (!found) {
                console.warn("User tidak ditemukan:", pegawai.id_user);
            }

            idOutletSelect.value = pegawai.id_outlet;
            jabatanInput.value = pegawai.jabatan;

            form.setAttribute("action", `/pegawai/${id}`);
            methodInput.value = "DELETE";
            submitBtn.textContent = "Hapus";
            submitBtn.className = "btn btn-danger";
            formTitle.textContent = "Hapus Pegawai";

            idUserSelect.disabled = true;
            idOutletSelect.disabled = true;
            jabatanInput.disabled = true;

            currentMode = 'delete';
        });
        }
    });

    document.getElementById("reset-btn").addEventListener("click", resetForm);
});
</script>
@endsection
