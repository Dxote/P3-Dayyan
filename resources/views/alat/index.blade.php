@extends('layouts.admin')

@section('main-content')
<h1 class="h3 mb-4 text-gray-800">{{ __('Data Alat') }}</h1>

@if (session('message'))
    <div class="alert alert-success">{{ session('message') }}</div>
@endif

<div class="row">
    <!-- TABEL DATA -->
    <div class="col-md-7">
        <div class="card shadow mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">Daftar Alat</h6>
                <div>
                    <a href="{{ route('alat.invoice') }}" class="btn btn-sm btn-info">Invoice</a>
                </div>
            </div>
            <div class="card-body">
            <div class="table-responsive">
            <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Kode Alat</th>
                            <th>Nama Alat</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($alat as $item)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $item->kode_alat }}</td>
                            <td>{{ $item->nama_alat }}</td>
                            <td>
                                <button class="btn btn-sm btn-primary edit-btn" data-id="{{ $item->kode_alat }}">Edit</button>
                                <form action="{{ route('alat.destroy', $item->kode_alat) }}" method="POST" style="display: inline-block;">
                                    @csrf
                                    @method('DELETE')
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
                <h6 id="form-title" class="m-0 font-weight-bold text-primary">Tambah Data</h6>
            </div>
            <div class="card-body">
                <form id="alat-form" method="POST" action="{{ route('alat.store') }}">
                    @csrf
                    <input type="hidden" id="form-method" name="_method" value="POST">

                    <div class="form-group">
                        <label for="kode_alat">Kode Alat</label>
                        <input type="text" class="form-control" id="kode_alat" name="kode_alat"
                            value="{{ autonumber('alat', 'kode_alat', 3, 'ALT') }}" readonly>
                    </div>

                    <div class="form-group">
                        <label for="nama_alat">Nama Alat</label>
                        <input type="text" class="form-control" id="nama_alat" name="nama_alat" required>
                    </div>

                    <button type="submit" class="btn btn-primary">Simpan</button>
                    <button type="button" id="reset-btn" class="btn btn-secondary">Reset</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- SCRIPT AJAX UNTUK EDIT DAN RESET FORM -->
<script>
document.addEventListener("DOMContentLoaded", function () {
    const form = document.getElementById('alat-form');
    const formTitle = document.getElementById('form-title');
    const kodeAlatInput = document.getElementById('kode_alat');
    const namaAlatInput = document.getElementById('nama_alat');
    const formMethod = document.getElementById('form-method');
    const resetBtn = document.getElementById('reset-btn');
    const addBtn = document.getElementById('add-btn');

    // **FUNGSI EDIT DATA**
    document.querySelectorAll('.edit-btn').forEach(button => {
        button.addEventListener('click', function () {
            let kode_alat = this.getAttribute('data-id');

            fetch(`/alat/${kode_alat}/edit`)
                .then(response => response.json())
                .then(data => {
                    form.action = `/alat/${kode_alat}`;
                    formMethod.value = "PUT";
                    kodeAlatInput.value = data.kode_alat;
                    namaAlatInput.value = data.nama_alat;
                    formTitle.textContent = "Edit Data";
                })
                .catch(error => console.error('Error:', error));
        });
    });

    // **FUNGSI RESET KE MODE TAMBAH**
    function resetForm() {
        form.action = "{{ route('alat.store') }}";
        formMethod.value = "POST";
        kodeAlatInput.value = "{{ autonumber('alat', 'kode_alat', 3, 'ALT') }}";
        namaAlatInput.value = "";
        formTitle.textContent = "Tambah Data";

        // Hapus input method PUT jika ada
        let putMethod = document.getElementById('put-method');
        if (putMethod) {
            putMethod.remove();
        }
    }

    // **EVENT LISTENER UNTUK RESET FORM**
    resetBtn.addEventListener('click', resetForm);
    addBtn.addEventListener('click', resetForm);
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