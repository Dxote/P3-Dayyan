@extends('layouts.admin')

@section('main-content')
<h1 class="h3 mb-4 text-gray-800">Data Supervisor</h1>

<div class="row">
    <!-- TABEL -->
    <div class="col-md-7">
        <div class="card shadow mb-4">
            <div class="card-header font-weight-bold text-primary">Daftar Supervisor</div>
            <div class="card-body">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Outlet</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($supervisors as $s)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $s->user->name ?? '-' }} {{ $s->user->last_name ?? '' }}</td>
                            <td>{{ $s->user->email ?? '-' }}</td>
                            <td>{{ $s->outlet->nama ?? '-' }}</td>
                            <td>
                                <!-- <button class="btn btn-sm btn-primary edit-btn" data-id="{{ $s->id_supervisor }}">Edit</button> -->
                                <button class="btn btn-sm btn-danger delete-btn" data-id="{{ $s->id_supervisor }}">Hapus</button>
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
                <h6 id="form-title" class="m-0">Tambah Supervisor</h6>
            </div>
            <div class="card-body">
                <form id="supervisor-form" action="{{ route('supervisor.store') }}" method="POST">
                    @csrf
                    <input type="hidden" id="form-method" name="_method" value="POST">

                    <div class="form-group">
                        <label for="id_user">Pilih User</label>
                        <select name="id_user" id="id_user" class="form-control" required>
                            <option value="">Pilih User</option>
                            @foreach ($users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }} {{ $user->last_name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="id_outlet">Pilih Outlet</label>
                        <select name="id_outlet" id="id_outlet" class="form-control" required>
                        <option value="">Pilih Outlet</option>
                        @foreach ($outlets as $outlet)
                            <option value="{{ $outlet->id_outlet }}">{{ $outlet->nama }}</option>
                        @endforeach
                    </select>
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
    const form = document.getElementById("supervisor-form");
    const methodInput = document.getElementById("form-method");
    const submitBtn = document.getElementById("submit-btn");
    const formTitle = document.getElementById("form-title");
    const idUserSelect = document.getElementById("id_user");
    const idOutletSelect = document.getElementById("id_outlet");

    let currentMode = 'create';

    function resetForm() {
        form.reset();
        form.setAttribute("action", "{{ route('supervisor.store') }}");
        methodInput.value = "POST";
        submitBtn.textContent = "Simpan";
        submitBtn.className = "btn btn-primary";
        formTitle.textContent = "Tambah Supervisor";
        idUserSelect.disabled = false;
        idOutletSelect.disabled = false;
        currentMode = 'create';
    }

    form.addEventListener("submit", function (e) {
        e.preventDefault();

        let actionUrl = form.getAttribute("action");
        let method = methodInput.value;

        const formData = new FormData(form);
        if (method === "DELETE") {
            formData.append('_method', 'DELETE');
        } else if (method === "PUT") {
            formData.append('_method', 'PUT');
        }

        fetch(actionUrl, {
            method: "POST",
            headers: {
                "X-CSRF-TOKEN": document.querySelector('input[name="_token"]').value,
                "Accept": "application/json"
            },
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                alert(`Data berhasil ${method === 'DELETE' ? 'dihapus' : 'disimpan'}!`);
                location.reload();
            } else {
                alert('Terjadi kesalahan saat menyimpan data.');
                console.error(data);
            }
        })
        .catch(err => {
            alert('Terjadi kesalahan jaringan.');
            console.error(err);
        });
    });

    document.addEventListener("click", function (e) {
        if (e.target.classList.contains("delete-btn")) {
            currentMode = 'delete';
            const id = e.target.getAttribute("data-id");

            fetch(`/supervisor/${id}/edit`)
            .then(res => res.json())
            .then(data => {
                const supervisor = data.supervisor;
                const user = data.user;

                // Cek dan tambahkan user jika tidak ada
                let userExist = [...idUserSelect.options].some(opt => opt.value == supervisor.id_user);
                if (!userExist) {
                    const newOption = document.createElement("option");
                    newOption.value = supervisor.id_user;
                    newOption.textContent = `${user?.name || 'User'} ${user?.last_name || ''}`;
                    newOption.selected = true;
                    idUserSelect.appendChild(newOption);
                }

                idUserSelect.value = supervisor.id_user;
                idOutletSelect.value = supervisor.id_outlet;

                form.setAttribute("action", `/supervisor/${id}`);
                methodInput.value = "DELETE";
                submitBtn.textContent = "Hapus";
                submitBtn.className = "btn btn-danger";
                formTitle.textContent = "Hapus Supervisor";

                idUserSelect.disabled = true;
                idOutletSelect.disabled = true;
            });
        }
    });

    document.getElementById("reset-btn").addEventListener("click", resetForm);
});
</script>

@endsection
