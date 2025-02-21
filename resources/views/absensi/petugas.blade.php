@extends('layouts.admin')

@section('main-content')
<div class="container">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h5 class="m-0">Jadwal Shift Anda Hari Ini</h5>
        </div>
        <div class="card-body">
        <p>Waktu Server: {{ now() }}</p>

            @if ($shifts->isEmpty())
                <div class="alert alert-warning text-center">Tidak ada jadwal shift untuk hari ini.</div>
            @else
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr class="text-center">
                                <th>Tanggal</th>
                                <th>Jam Mulai</th>
                                <th>Jam Selesai</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($shifts as $shift)
                            @php
                                $sudahAbsen = $shift->absensi && $shift->absensi->status !== 'tanpa keterangan';
                            @endphp

                                <tr>
                                    <td>{{ $shift->tanggal_shift }}</td>
                                    <td>{{ $shift->jam_mulai }}</td>
                                    <td>{{ $shift->jam_selesai }}</td>
                                    <td class="text-center">
                                        @if ($sudahAbsen)
                                            <button class="btn btn-secondary w-100" disabled>
                                                <i class="fas fa-check"></i> Sudah Absen
                                            </button>
                                        @else
                                            @if ($shift->shift_belum_mulai)
                                                <button class="btn btn-warning w-100" disabled>
                                                    <i class="fas fa-clock"></i> Shift Belum Mulai
                                                </button>
                                            @elseif ($shift->expired)
                                                <form class="form-absen" data-kode="{{ $shift->kode_shift }}">
                                                    @csrf
                                                    <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
                                                    <input type="hidden" name="kode_shift" value="{{ $shift->kode_shift }}">
                                                    <input type="hidden" name="tanggal_absen" value="{{ now()->toDateString() }}">
                                                    <div class="mb-2">
                                                        <input type="radio" name="status" value="sakit" required>
                                                        <label>Sakit</label>
                                                    </div>
                                                    <div class="mb-2">
                                                        <input type="radio" name="status" value="izin">
                                                        <label>Izin</label>
                                                    </div>
                                                    <div class="mb-3">
                                                        <input type="text" name="keterangan" class="form-control keterangan" placeholder="Masukkan keterangan" required>
                                                    </div>
                                                    <button type="submit" class="btn btn-primary w-100 submit-absen">Submit</button>
                                                </form>
                                            @else
                                                <button class="btn btn-success w-100 absen-btn" data-kode="{{ $shift->kode_shift }}">
                                                    Hadir
                                                </button>
                                            @endif
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll('.form-absen').forEach(form => {
        form.addEventListener('submit', function (event) {
            event.preventDefault();

            let kodeShift = this.getAttribute('data-kode');
            let status = this.querySelector('input[name="status"]:checked')?.value;
            let keterangan = this.querySelector('.keterangan').value;
            let submitButton = this.querySelector('.submit-absen');

            if (!status || !keterangan) {
                alert("Silakan pilih status dan isi keterangan!");
                return;
            }

            submitButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Memproses...';
            submitButton.disabled = true;

            fetch("{{ route('absensi.hadir') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                body: JSON.stringify({ kode_shift: kodeShift, status: status, keterangan: keterangan })
            })
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    alert("Gagal: " + data.error);
                } else {
                    alert("Berhasil: " + data.message);
                    form.innerHTML = '<button class="btn btn-secondary w-100" disabled><i class="fas fa-check"></i> Sudah Absen</button>';
                }
            })
            .catch(error => {
                alert("Terjadi kesalahan saat absen.");
                console.error('Error:', error);
            })
            .finally(() => {
                submitButton.innerHTML = "Submit";
                submitButton.disabled = false;
            });
        });
    });

    document.querySelectorAll('.absen-btn').forEach(button => {
        button.addEventListener('click', function(event) {
            event.preventDefault();

            let kodeShift = this.getAttribute('data-kode');
            let btn = this;

            if (confirm("Konfirmasi Absen?\nPastikan Anda benar-benar hadir sebelum absen.")) {
                btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Memproses...';
                btn.disabled = true;

                fetch("{{ route('absensi.hadir') }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": "{{ csrf_token() }}"
                    },
                    body: JSON.stringify({ kode_shift: kodeShift })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.error) {
                        alert("Gagal: " + data.error);
                        btn.innerHTML = "Hadir";
                        btn.disabled = false;
                    } else {
                        alert("Berhasil: " + data.message);
                        btn.innerHTML = '<i class="fas fa-check"></i> Sudah Absen';
                        btn.classList.remove("btn-success");
                        btn.classList.add("btn-secondary");
                    }
                })
                .catch(error => {
                    alert("Terjadi kesalahan saat absen.");
                    console.error('Error:', error);
                });
            }
        });
    });
});
</script>
@endsection
