<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SparePart extends Model
{
    use HasFactory;
    protected $table = 'sparepart';
    protected $primaryKey = 'kode_sparepart';
    protected $keyType = 'string';
    public $incrementing = false;
    protected $fillable = [
        'kode_sparepart',
        'nama_sparepart',
        'stok',
        'harga',
        'jumlah_satuan',
        'kode_satuan',
        'kode_brand',
    ];

    public function satuan()
    {
        return $this->belongsTo(Satuan::class, 'kode_satuan', 'kode_satuan');
    }
    public function brand()
    {
        return $this->belongsTo(Brand::class, 'kode_brand', 'kode_brand');
    }
}
