@extends('layouts.admin')

@section('main-content')
<h1 class="h3 mb-4 text-gray-800">{{ __('Data Shift') }}</h1>

@if (session('message'))
    <div class="alert alert-success">{{ session('message') }}</div>
@endif

<div class="row">
    <!-- TABEL DATA SHIFT -->
    <div class="col-md-7">
        <div class="card shadow mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">Daftar Shift</h6>
                <div>
                    <a href="{{ route('shift.invoice') }}" class="btn btn-sm btn-info">Invoice</a>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Kode Shift</th>
                                <th>Nama Karyawan</th>
                                <th>Tanggal</th>
                                <th>Jam Mulai</th>
                                <th>Jam Selesai</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($shifts as $shift)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $shift->kode_shift }}</td>
                                <td>{{ $shift->user->name }}</td>
                                <td>{{ $shift->tanggal_shift }}</td>
                                <td>{{ $shift->jam_mulai }}</td>
                                <td>{{ $shift->jam_selesai }}</td>
                                <td>
                                    <button class="btn btn-sm btn-primary edit-btn" data-id="{{ $shift->kode_shift }}">Edit</button>
                                    <form action="{{ route('shift.destroy', $shift->kode_shift) }}" method="POST" class="d-inline">
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

    <!-- FORM TAMBAH & EDIT SHIFT -->
    <div class="col-md-5">
        <div class="card shadow mb-4">
            <div class="card-header bg-primary text-white">
                <h6 id="form-title" class="m-0">Tambah Shift</h6>
            </div>
            <div class="card-body">
                <form id="shift-form" method="POST" action="{{ route('shift.store') }}">
                    @csrf
                    <input type="hidden" id="form-method" name="_method" value="POST">
                    <div class="form-group">
                        <label for="kode_shift">Kode Shift</label>
                        <input type="text" class="form-control" id="kode_shift" name="kode_shift"
                               value="{{ autonumber('shift', 'kode_shift', 3, 'SFT') }}" readonly>
                    </div>

                    <div class="form-group">
                        <label for="user_id">Nama Karyawan</label>
                        <select class="form-control" id="user_id" name="user_id">
                            <option value="" selected>Pilih Karyawan</option>
                            @foreach ($users as $user)
                                @if ($user->level == 'petugas') {{-- Sesuaikan dengan nama role yang ada di database --}}
                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="tanggal_shift">Tanggal Shift</label>
                        <input type="date" class="form-control" id="tanggal_shift" name="tanggal_shift" required>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="jam_mulai">Jam Mulai</label>
                                <input type="time" class="form-control" id="jam_mulai" name="jam_mulai" required>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="jam_selesai">Jam Selesai</label>
                                <input type="time" class="form-control" id="jam_selesai" name="jam_selesai" required>
                            </div>
                        </div>
                    </div>

                    <div class="text-right">
                        <button type="submit" class="btn btn-primary">Simpan</button>
                        <button type="button" id="reset-btn" class="btn btn-secondary">Reset</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- SCRIPT AJAX UNTUK EDIT DAN RESET FORM -->
<script>
document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll('.edit-btn').forEach(button => {
        button.addEventListener('click', function () {
            let kode_shift = this.getAttribute('data-id');

            fetch(`/shift/${kode_shift}/edit`)
                .then(response => {
                    if (!response.ok) throw new Error("Gagal mengambil data");
                    return response.json();
                })
                .then(data => {
                    const shift = data.shift;
                    document.getElementById('shift-form').action = `/shift/${shift.kode_shift}`;
                    document.getElementById('form-method').value = "PUT";
                    document.getElementById('kode_shift').value = shift.kode_shift;
                    document.getElementById('user_id').value = shift.user_id;
                    document.getElementById('tanggal_shift').value = shift.tanggal_shift;
                    document.getElementById('jam_mulai').value = shift.jam_mulai;
                    document.getElementById('jam_selesai').value = shift.jam_selesai;
                    document.getElementById('form-title').textContent = "Edit Shift";
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert("Terjadi kesalahan saat mengambil data.");
                });
        });
    });

    document.getElementById('reset-btn').addEventListener('click', function () {
        document.getElementById('shift-form').reset();
        document.getElementById('shift-form').action = "{{ route('shift.store') }}";
        document.getElementById('form-method').value = "POST";
        document.getElementById('form-title').textContent = "Tambah Shift";
    });
});
</script>

@endsection
