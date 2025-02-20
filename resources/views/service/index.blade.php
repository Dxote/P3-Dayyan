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
                <form id="service-form" method="POST">
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
                        <label>Sparepart</label>
                        <div class="checkbox-group">
                            @foreach ($sparepart as $sp)
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="sparepart_{{ $sp->kode_sparepart }}" name="sparepart[]" value="{{ $sp->kode_sparepart }}">
                                    <label class="form-check-label" for="sparepart_{{ $sp->kode_sparepart }}">{{ $sp->nama_sparepart }}</label>
                                </div>
                            @endforeach
                        </div>
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
        // Simpan & Update Data
        $('#service-form').on('submit', function (e) {
            e.preventDefault();

            let formData = new FormData(this);
            let method = $('#form-method').val();
            let actionUrl = (method === 'POST') ? `{{ url('service') }}` : $('#service-form').attr('action');

            $.ajax({
                url: actionUrl,
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function (response) {
        alert(response.message || "Data berhasil diproses!");
        location.reload();
    },
                error: function (xhr) {
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

                    // Reset checkbox
                    $('input[name="sparepart[]"]').prop('checked', false);
                    $('input[name="alat[]"]').prop('checked', false);

                    // Isi checkbox Sparepart
                    if (data.selected_sparepart) {
                        data.selected_sparepart.forEach(value => {
                            $(`#sparepart_${value}`).prop('checked', true);
                        });
                    }

                    // Isi checkbox Alat
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

        // Hapus Data (Event Delegation)
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
        });
    });
</script>
@endsection