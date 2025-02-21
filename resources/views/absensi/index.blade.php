@extends('layouts.admin')

@section('main-content')
<h1 class="h3 mb-4 text-gray-800">{{ __('Data Absensi') }}</h1>

@if (session('message'))
    <div class="alert alert-success">{{ session('message') }}</div>
@endif

<div class="row">
    <!-- TABEL DATA ABSENSI -->
    <div class="col-md-7">
        <div class="card shadow mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">Daftar Absensi</h6>
                <div>
                <a href="{{ route('absensi.invoice') }}" class="btn btn-sm btn-info">Invoice</a>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Kode Absen</th>
                                <th>Nama Karyawan</th>
                                <th>Shift</th>
                                <th>Tanggal</th>
                                <th>Jam Absen</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($absensi as $absen)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $absen->kode_absen }}</td>
                                    <td>{{ $absen->user->name }}</td>
                                    <td>
                                        @if($absen->shift)
                                            {{ \Carbon\Carbon::parse($absen->shift->tanggal_shift)->format('d/m/Y') }} - 
                                            {{ \Carbon\Carbon::parse($absen->shift->jam_mulai)->format('H:i') }} - 
                                            {{ \Carbon\Carbon::parse($absen->shift->jam_selesai)->format('H:i') }}
                                        @else
                                            Tidak Ada Shift
                                        @endif
                                    </td>
                                    <td>{{ $absen->tanggal_absen->format('d/m/Y') }}</td>
                                    <td>{{ $absen->jam_absen }}</td>
                                    <td>
                                        @if ($absen->status === 'hadir')
                                            Hadir
                                        @else
                                            {{ $absen->status }} - {{ $absen->keterangan }}
                                        @endif
                                    </td>
                                    <td>
                                        <button class="btn btn-sm btn-primary edit-btn" data-id="{{ $absen->kode_absen }}">Edit</button>
                                        <form action="{{ route('absensi.destroy', $absen->kode_absen) }}" method="POST" class="d-inline">
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

    <!-- FORM TAMBAH & EDIT ABSENSI -->
    <div class="col-md-5">
        <div class="card shadow mb-4">
            <div class="card-header bg-primary text-white">
                <h6 id="form-title" class="m-0">Tambah Absensi</h6>
            </div>
            <div class="card-body">
                <form id="absensi-form" method="POST" action="{{ route('absensi.store') }}">
                    @csrf
                    <input type="hidden" id="form-method" name="_method" value="POST">
                    
                    <div class="form-group">
                        <label for="kode_absen">Kode Absen</label>
                        <input type="text" class="form-control" id="kode_absen" name="kode_absen" value="{{ autonumber('absensi', 'kode_absen', 3, 'ABN') }}" readonly>
                    </div>

                    <div class="form-group">
                        <label for="user_id">Nama Karyawan</label>
                        <select class="form-control" id="user_id" name="user_id">
                            <option value="" selected>Pilih Karyawan</option>
                            @foreach ($users as $user)
                                @if ($user->level == 'petugas') 
                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="kode_shift">Shift</label>
                        <select class="form-control" id="kode_shift" name="kode_shift">
                            <option value="" selected>Pilih Shift</option>
                            @foreach ($shifts as $shift)
                                <option value="{{ $shift->kode_shift }}">
                                    {{ \Carbon\Carbon::parse($shift->tanggal_shift)->format('d/m/Y') }} - 
                                    {{ \Carbon\Carbon::parse($shift->jam_mulai)->format('H:i') }} - 
                                    {{ \Carbon\Carbon::parse($shift->jam_selesai)->format('H:i') }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="tanggal_absen">Tanggal</label>
                        <input type="date" class="form-control" id="tanggal_absen" name="tanggal_absen" required>
                    </div>

                    <div class="form-group">
                        <label for="status">Status</label>
                        <select class="form-control" id="status" name="status">
                            <option value="hadir">Hadir</option>
                            <option value="izin">Izin</option>
                            <option value="sakit">Sakit</option>
                            <option value="tanpa keterangan">Tanpa Keterangan</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="keterangan">Keterangan</label>
                        <input type="date" class="form-control" id="keterangan" name="keterangan" required>
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
            let kode_absen = this.getAttribute('data-id');

            fetch(`/absensi/${kode_absen}/edit`)
                .then(response => {
                    if (!response.ok) throw new Error("Gagal mengambil data");
                    return response.json();
                })
                .then(data => {
                    const absen = data.absensi;
                    document.getElementById('absensi-form').action = `/absensi/${absen.kode_absen}`;
                    document.getElementById('form-method').value = "PUT";
                    document.getElementById('kode_absen').value = absen.kode_absen;
                    document.getElementById('user_id').value = absen.user_id;

                    // Mengatur ulang dropdown shift dengan format yang diinginkan
                    let kodeShiftSelect = document.getElementById('kode_shift');
                    kodeShiftSelect.innerHTML = `<option value="" selected>Pilih Shift</option>`;
                    data.shifts.forEach(shift => {
                        let option = document.createElement("option");
                        option.value = shift.kode_shift;
                        option.text = `${shift.tanggal_shift} - ${shift.jam_mulai} - ${shift.jam_selesai}`;
                        if (shift.kode_shift === absen.kode_shift) {
                            option.selected = true;
                        }
                        kodeShiftSelect.appendChild(option);
                    });

                    document.getElementById('tanggal_absen').value = absen.tanggal_absen;
                    document.getElementById('status').value = absen.status;
                    document.getElementById('keterangan').value = absen.keterangan;
                    document.getElementById('form-title').textContent = "Edit Absensi";
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert("Terjadi kesalahan saat mengambil data.");
                });
        });
    });

    document.getElementById('reset-btn').addEventListener('click', function () {
        document.getElementById('absensi-form').reset();
        document.getElementById('absensi-form').action = "{{ route('absensi.store') }}";
        document.getElementById('form-method').value = "POST";
        document.getElementById('form-title').textContent = "Tambah Absensi";
    });
});
</script>

@endsection
