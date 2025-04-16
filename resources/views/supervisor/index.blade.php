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
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($supervisors as $s)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $s->user->name ?? '-' }} {{ $s->user->last_name ?? '' }}</td>
                            <td>{{ $s->user->email ?? '-' }}</td>
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

    let currentMode = 'create';

    function resetForm() {
        form.reset();
        form.setAttribute("action", "{{ route('supervisor.store') }}");
        methodInput.value = "POST";
        submitBtn.textContent = "Simpan";
        submitBtn.className = "btn btn-primary";
        formTitle.textContent = "Tambah Supervisor";
        idUserSelect.disabled = false;
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
        if (e.target.classList.contains("delete-btn")) {
            const id = e.target.getAttribute("data-id");

            fetch(`/supervisor/${id}/edit`)
            .then(res => res.json())
            .then(data => {
                const supervisor = data.supervisor;
                const user = data.user;

                let userExist = [...idUserSelect.options].some(opt => opt.value == supervisor.id_user);
                if (!userExist) {
                    const newOption = document.createElement("option");
                    newOption.value = supervisor.id_user;
                    newOption.textContent = `${supervisor.user.name} (${supervisor.user.email})`;
                    newOption.selected = true;
                    idUserSelect.appendChild(newOption);
                }

                idUserSelect.value = supervisor.id_user;

                form.setAttribute("action", `/supervisor/${id}`);
                methodInput.value = "DELETE";
                submitBtn.textContent = "Hapus";
                submitBtn.className = "btn btn-danger";
                formTitle.textContent = "Hapus Supervisor";

                idUserSelect.disabled = true;
                currentMode = 'delete';
            });
        }
    });

    document.getElementById("reset-btn").addEventListener("click", resetForm);
});
</script>
@endsection
