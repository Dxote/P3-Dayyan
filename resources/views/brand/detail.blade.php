@extends('layouts.admin')

@section('main-content')
    <h1 class="h3 mb-4 text-gray-800">Detail Brand: {{ $brand->brand }}</h1>

    <div class="d-flex justify-content-end mt-4">
    <button class="btn btn-primary mr-2" onclick="window.print()">Print</button>
    <a href="{{ route('brand.index') }}" class="btn btn-secondary">Kembali</a>
</div>

<br>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Nama Sparepart</th>
                <th>Stok</th>
                <th>Harga</th>
                <th>Satuan</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($brand->spareparts as $sparepart)
                <tr>
                    <td>{{ $sparepart->nama_sparepart }}</td>
                    <td>{{ $sparepart->stok }}</td>
                    <td>{{ number_format($sparepart->harga, 0, ',', '.') }}</td>
                    <td>{{ number_format($sparepart->jumlah_satuan, 0, ',', '.') }} {{ $sparepart->satuan->nama_satuan }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
