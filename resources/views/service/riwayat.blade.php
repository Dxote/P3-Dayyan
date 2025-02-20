@extends('layouts.admin')

@section('main-content')
<h1 class="h3 mb-4 text-gray-800">{{ __('Riwayat Service Saya') }}</h1>

<div class="row">
    <div class="col-md-12">
        <div class="card shadow mb-4">
            <div class="card-header">
                <h6 class="m-0 font-weight-bold text-primary">Daftar Riwayat Service</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead class="text-center">
                            <tr>
                                <th>No</th>
                                <th>Keluhan</th>
                                <th>Plat Nomor</th>
                                <th>Nama Motor</th>
                                <th>Petugas</th>
                                <th>Sparepart</th>
                                <th>Alat</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($services as $item)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $item->deskripsi_masalah }}</td>
                                <td>{{ $item->plat_nomor }}</td>
                                <td>{{ $item->nama_motor }}</td>
                                <td>{{ $item->petugas->name ?? '-' }}</td>
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
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center">Belum ada riwayat service.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
