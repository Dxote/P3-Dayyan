@extends('layouts.admin')

@section('main-content')
<h1 class="h3 mb-4 text-gray-800">{{ __('Data Satuan') }}</h1>

@if (session('message'))
    <div class="alert alert-success">{{ session('message') }}</div>
@endif

<div class="row">
    <!-- TABEL DATA -->
    <div class="col-md-7">
        <div class="card shadow mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">Daftar Satuan</h6>
                <div>
                    <a href="{{ route('satuan.invoice') }}" class="btn btn-sm btn-info">Invoice</a>
                </div>
            </div>
            <div class="card-body">
            <div class="table-responsive">
            <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Kode Satuan</th>
                            <th>Nama Satuan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($satuan as $item)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $item->kode_satuan }}</td>
                            <td>{{ $item->nama_satuan }}</td>
                            <td>
                                <button class="btn btn-sm btn-primary edit-btn"
                                    data-id="{{ $item->kode_satuan }}">Edit</button>
                                <form action="{{ route('satuan.destroy', $item->kode_satuan) }}" method="POST"
                                    style="display: inline-block;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger"
                                        onclick="return confirm('Yakin ingin menghapus?')">Hapus</button>
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
            <form id="satuan-form" method="POST" action="{{ route('satuan.store') }}">
    @csrf
    <input type="hidden" id="form-method" name="_method" value="POST">

    <div class="form-group">
        <label for="kode_satuan">Kode Satuan</label>
        <input type="text" class="form-control" id="kode_satuan" name="kode_satuan"
            value="{{ autonumber('satuan', 'kode_satuan', 3, 'STN') }}" readonly>
    </div>

    <div class="form-group">
        <label for="nama_satuan">Nama Satuan</label>
        <input type="text" class="form-control" id="nama_satuan" name="nama_satuan" required>
    </div>

    <button type="submit" class="btn btn-primary">Simpan</button>
    <button type="button" id="reset-btn" class="btn btn-secondary">Reset</button>
</form>


            </div>
        </div>
    </div>
</div>

<!-- SCRIPT AJAX UNTUK EDIT DAN TAMBAH -->
<script>
    document.addEventListener("DOMContentLoaded", function () {
    const form = document.getElementById('brand-form');
    const formTitle = document.getElementById('form-title');
    const kodeBrandInput = document.getElementById('kode_brand');
    const namaBrandInput = document.getElementById('brand');
    const formMethod = document.getElementById('form-method');
    const resetBtn = document.getElementById('reset-btn');
    const addBtn = document.getElementById('add-btn');

    // **FUNGSI EDIT DATA**
    document.querySelectorAll('.edit-btn').forEach(button => {
        button.addEventListener('click', function () {
            let kode_brand = this.getAttribute('data-id');

            fetch(`/brand/${kode_brand}/edit`)
    .then(response => response.json())
    .then(data => {
        form.action = `/brand/${kode_brand}`;
        document.getElementById('form-method').value = "PUT";
        kodeBrandInput.value = data.kode_brand;
        namaBrandInput.value = data.brand;
        formTitle.textContent = "Edit Brand";
    })
    .catch(error => console.error('Error:', error));

        });
    });

    // **FUNGSI RESET KE MODE TAMBAH**
    function resetForm() {
        form.action = "{{ route('brand.store') }}";
        formMethod.value = "POST";
        kodeBrandInput.value = "{{ autonumber('brand', 'kode_brand', 3, 'BRD') }}";
        namaBrandInput.value = "";
        formTitle.textContent = "Tambah Brand";

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