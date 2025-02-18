@extends('layouts.admin')

@section('main-content')
<h1 class="h3 mb-4 text-gray-800">{{ __('Data Brand') }}</h1>

@if (session('message'))
    <div class="alert alert-success">{{ session('message') }}</div>
@endif

<div class="row">
    <!-- TABEL DATA -->
    <div class="col-md-7">
        <div class="card shadow mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">Daftar Brand</h6>
                <a href="{{ route('brand.invoice') }}" id="add-btn" class="btn btn-sm btn-success">Invoice</a>
            </div>
            <div class="card-body">
            <div class="table-responsive">
            <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Kode Brand</th>
                            <th>Nama Brand</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($brand as $item)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $item->kode_brand }}</td>
                            <td>{{ $item->brand }}</td>
                            <td>
                                <button class="btn btn-sm btn-primary edit-btn"
                                    data-id="{{ $item->kode_brand }}">Edit</button>
                                <form action="{{ route('brand.destroy', $item->kode_brand) }}" method="POST"
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
            <form id="brand-form" method="POST" action="{{ route('brand.store') }}">
    @csrf
    <input type="hidden" id="form-method" name="_method" value="POST">
    
    <div class="form-group">
        <label for="kode_brand">Kode Brand</label>
        <input type="text" class="form-control" id="kode_brand" name="kode_brand"
                            value="{{ autonumber('brand', 'kode_brand', 3, 'BRD') }}" readonly>
    </div>

    <div class="form-group">
        <label for="brand">Nama Brand</label>
        <input type="text" class="form-control" id="brand" name="brand" required>
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

    document.querySelectorAll('.edit-btn').forEach(button => {
        button.addEventListener('click', function () {
            let kode_brand = this.getAttribute('data-id');

            fetch(`/brand/${kode_brand}/edit`)
                .then(response => response.json())
                .then(data => {
                    console.log("Data diterima:", data);

                    // Pastikan action menggunakan PUT
                    form.action = `/brand/${kode_brand}`;
                    formMethod.value = "PUT"; 

                    kodeBrandInput.value = data.kode_brand;
                    namaBrandInput.value = data.brand;
                    formTitle.textContent = "Edit Brand";
                })
                .catch(error => {
                    console.error('Fetch Error:', error);
                    alert("Terjadi kesalahan saat mengambil data.");
                });
        });
    });

    function resetForm() {
        form.action = "{{ route('brand.store') }}";
        formMethod.value = "POST"; // Kembali ke mode tambah
        kodeBrandInput.value = "{{ autonumber('brand', 'kode_brand', 3, 'BRD') }}";
        namaBrandInput.value = "";
        formTitle.textContent = "Tambah Brand";
    }

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
