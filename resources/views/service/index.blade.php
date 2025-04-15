@extends('layouts.admin')

@section('main-content')
<h1 class="h3 mb-4 text-gray-800">{{ __('Data Service') }}</h1>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

@if (session('message'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <strong>Sukses!</strong> {{ session('message') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
@endif

<div class="row">
    <!-- TABEL DATA SERVICE -->
    <div class="col-md-7">
        <div class="card shadow mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">Daftar Service</h6>
                <a href="{{ route('service.invoice') }}" class="btn btn-sm btn-info">Invoice</a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead class="text-center">
                            <tr>
                                <th>No</th>
                                <th>Kode Service</th>
                                <th>Plat Nomor</th>
                                <th>Nama Motor</th>
                                <th>Petugas</th>
                                <th>Pelanggan</th>
                                <th>Sparepart</th>
                                <th>Alat</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="service-table">
                            @foreach ($service as $item)
                            <tr id="row-{{ $item->kode_service }}">
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $item->kode_service }}</td>
                                <td>{{ $item->plat_nomor }}</td>
                                <td>{{ $item->nama_motor }}</td>
                                <td>{{ $item->petugas->name ?? '-' }}</td>
                                <td>{{ $item->pengguna->name ?? '-' }}</td>
                                <td>
                                    @foreach ($item->serviceSpareparts as $sp)
                                        <span class="badge badge-success">{{ $sp->sparepart->nama_sparepart }}</span>
                                    @endforeach
                                </td>
                                <td>
                                    @foreach ($item->serviceAlat as $al)
                                        <span class="badge badge-primary">{{ $al->alat->nama_alat }}</span>
                                    @endforeach
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-primary edit-btn" data-id="{{ $item->kode_service }}">Edit</button>
                                    <button class="btn btn-sm btn-danger delete-btn" data-id="{{ $item->kode_service }}">Hapus</button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- FORM TAMBAH & EDIT SERVICE -->
    <div class="col-md-5">
        <div class="card shadow mb-4">
            <div class="card-header bg-primary text-white">
                <h6 id="form-title" class="m-0">Tambah Data</h6>
            </div>
            <div class="card-body">
                <form id="service-form" action="{{ url('service') }}" method="POST">
                    @csrf
                    <input type="hidden" id="form-method" name="_method" value="POST">
                    <div class="form-group">
                        <label for="kode_service">Kode Service</label>
                        <input type="text" class="form-control" id="kode_service" name="kode_service" 
                        value="{{ autonumber('service', 'kode_service', 3, 'SVC') }}" readonly>
                    </div>

                    <div class="form-group">
                        <label for="plat_nomor">Plat Nomor</label>
                        <input type="text" class="form-control" id="plat_nomor" name="plat_nomor" required>
                    </div>

                    <div class="form-group">
                        <label for="nama_motor">Nama Motor</label>
                        <input type="text" class="form-control" id="nama_motor" name="nama_motor" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="kode_brand">Brand</label>
                        <select class="form-control" id="kode_brand" name="kode_brand">
                            <option value="">Pilih Brand</option>
                            @foreach ($brand as $b)
                                <option value="{{ $b->kode_brand }}">{{ $b->brand }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="petugas_id">Petugas</label>
                        <select class="form-control" id="petugas_id" name="petugas_id">
                            <option value="">Pilih Petugas</option>
                            @foreach ($petugas as $p)
                                <option value="{{ $p->id }}">{{ $p->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="user_id">Pelanggan</label>
                        <select class="form-control" id="user_id" name="user_id">
                            <option value="">Pilih User</option>
                            @foreach ($pengguna as $u)
                                <option value="{{ $u->id }}">{{ $u->name }}</option>
                            @endforeach
                        </select>
                    </div>


                    <div class="form-group">
                        <label for="deskripsi_masalah">Deskripsi Masalah</label>
                        <textarea class="form-control" id="deskripsi_masalah" name="deskripsi_masalah" rows="3" required></textarea>
                    </div>

                    <div class="form-group">
                        <label>Alat</label>
                        <div class="checkbox-group">
                            @foreach ($alat as $al)
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="alat_{{ $al->kode_alat }}" name="alat[]" value="{{ $al->kode_alat }}">
                                    <label class="form-check-label" for="alat_{{ $al->kode_alat }}">{{ $al->nama_alat }}</label>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="sparepart">Sparepart</label>
                        <div class="d-flex">
                            <select class="form-control" id="sparepart">
                                <option value="">Pilih Sparepart</option>
                                @foreach ($sparepart as $sp)
                                    <option value="{{ $sp->kode_sparepart }}" data-nama="{{ $sp->nama_sparepart }}">{{ $sp->nama_sparepart }}</option>
                                @endforeach
                            </select>
                            <button type="button" id="tambah-sparepart" class="btn btn-success ml-2">Tambah</button>
                        </div>
                    </div>

                    <!-- Daftar sparepart yang dipilih -->
                    <div id="daftar-sparepart" class="mt-3"></div>


                    <div class="text-right">
                        <button type="submit" class="btn btn-primary">Simpan</button>
                        <button type="button" id="reset-btn" class="btn btn-secondary">Reset</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
    let daftarSparepart = [];

    // Fungsi Render Daftar Sparepart
    function renderSparepart() {
        $('#daftar-sparepart').html('');
        daftarSparepart.forEach((sp, index) => {
            $('#daftar-sparepart').append(`
                <div class="d-flex align-items-center mb-2">
                    <button class="btn btn-sm btn-danger mr-2 hapus-sparepart" data-index="${index}">×</button>
                    <span class="mr-2">${sp.nama_sparepart}</span>
                    <button class="btn btn-sm btn-secondary kurang-jumlah" data-index="${index}">−</button>
                    <input type="text" class="form-control form-control-sm mx-2 jumlah-sparepart" data-index="${index}" value="${sp.jumlah}" style="width: 50px; text-align:center;">
                    <button class="btn btn-sm btn-secondary tambah-jumlah" data-index="${index}">+</button>
                </div>
            `);
        });
    }
    $('#tambah-sparepart').click(function () {
        let kodeSparepart = $('#sparepart').val();
        let namaSparepart = $('#sparepart option:selected').text();

        if (kodeSparepart && !daftarSparepart.some(s => s.kode_sparepart === kodeSparepart)) {
            daftarSparepart.push({ kode_sparepart: kodeSparepart, nama_sparepart: namaSparepart, jumlah: 1 });
            renderSparepart();
        }
    });

    // Checkbox Sparepart Handler
    $('input[name="sparepart[]"]').change(function () {
        let kodeSparepart = $(this).val();
        let namaSparepart = $(this).data('nama');

        if ($(this).is(':checked')) {
            if (!daftarSparepart.some(sp => sp.kode_sparepart === kodeSparepart)) {
                daftarSparepart.push({ kode_sparepart: kodeSparepart, nama_sparepart: namaSparepart, jumlah: 1 });
            }
        } else {
            daftarSparepart = daftarSparepart.filter(sp => sp.kode_sparepart !== kodeSparepart);
        }
        renderSparepart();
    });

    // Tambah Jumlah Sparepart
    $(document).on('click', '.tambah-jumlah', function () {
        let index = $(this).data('index');
        daftarSparepart[index].jumlah++;
        renderSparepart();
    });

    // Kurangi Jumlah Sparepart
    $(document).on('click', '.kurang-jumlah', function () {
        let index = $(this).data('index');
        if (daftarSparepart[index].jumlah > 1) {
            daftarSparepart[index].jumlah--;
        }
        renderSparepart();
    });

    // Hapus Sparepart dari Daftar
    $(document).on('click', '.hapus-sparepart', function () {
        let index = $(this).data('index');
        let kodeSparepart = daftarSparepart[index].kode_sparepart;
        daftarSparepart.splice(index, 1);
        $(`input[name="sparepart[]"][value="${kodeSparepart}"]`).prop('checked', false);
        renderSparepart();
    });

    // Submit Form dengan AJAX
        $('#service-form').on('submit', function (e) {
        e.preventDefault();

        let formData = new FormData(this);
        formData.append('sparepart', JSON.stringify(daftarSparepart));

        let method = $('#form-method').val();
        let actionUrl = (method === 'POST') ? `{{ url('service') }}` : $('#service-form').attr('action');

        $.ajax({
            url: actionUrl,
            type: method,
            data: formData,
            contentType: false,
            processData: false,
            success: function (response) {
                alert(response.message || "Data berhasil diproses!");
                location.reload();
            },
            error: function (xhr) {
                    console.log(xhr.responseText); 
                alert('Terjadi kesalahan, silakan coba lagi.');
            }
        });
    });

    // Edit Data
    $(document).on('click', '.edit-btn', function () {
        let id = $(this).data('id');

        $.ajax({
            url: `{{ url('service') }}/${id}/edit`,
            type: 'GET',
            success: function (data) {
                if (!data.service) {
                    alert("Data tidak ditemukan.");
                    return;
                }

                $('#form-title').text('Edit Data');
                $('#form-method').val('PUT');
                $('#service-form').attr('action', `{{ url('service') }}/${id}`);
                
                $('#kode_service').val(data.service.kode_service).prop('readonly', true);
                $('#plat_nomor').val(data.service.plat_nomor);
                $('#nama_motor').val(data.service.nama_motor);
                $('#kode_brand').val(data.service.kode_brand);
                $('#deskripsi_masalah').val(data.service.deskripsi_masalah);
                $('#petugas_id').val(data.service.petugas_id);
                $('#user_id').val(data.service.user_id);

                // Reset Sparepart & Alat
                $('input[name="sparepart[]"], input[name="alat[]"]').prop('checked', false);
                daftarSparepart = [];

                // Isi Sparepart
                if (data.selected_sparepart) {
                    data.selected_sparepart.forEach(sp => {
                        daftarSparepart.push({ kode_sparepart: sp.kode_sparepart, nama_sparepart: sp.nama_sparepart, jumlah: sp.jumlah });
                        $(`input[name="sparepart[]"][value="${sp.kode_sparepart}"]`).prop('checked', true);
                    });
                }
                renderSparepart();

                // Isi Alat
                if (data.selected_alat) {
                    data.selected_alat.forEach(value => {
                        $(`#alat_${value}`).prop('checked', true);
                    });
                }
            },
            error: function (xhr) {
                console.log(xhr.responseText);
                alert('Terjadi kesalahan, tidak dapat mengambil data.');
            }
        });
    });

    // Hapus Data
    $(document).on('click', '.delete-btn', function () {
        let id = $(this).data('id');

        if (confirm('Apakah Anda yakin ingin menghapus data ini?')) {
            $.ajax({
                url: `{{ url('service') }}/${id}`,
                type: 'DELETE',
                data: {
                    _token: "{{ csrf_token() }}"
                },
                success: function (response) {
                    alert(response.message);
                    location.reload();
                },
                error: function () {
                    alert('Terjadi kesalahan, gagal menghapus data.');
                }
            });
        }
    });

    // Reset Form
    $('#reset-btn').on('click', function () {
        $('#form-title').text('Tambah Data');
        $('#form-method').val('POST');
        $('#service-form').attr('action', `{{ url('service') }}`);
        $('#service-form')[0].reset();
        $('input[name="sparepart[]"], input[name="alat[]"]').prop('checked', false);
        $('#kode_service').val("{{ autonumber('service', 'kode_service', 3, 'SVC') }}").prop('readonly', true);
        daftarSparepart = [];
        renderSparepart();
    });
});

</script>
@endsection