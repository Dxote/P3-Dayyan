<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Masuk extends Model
{
    use HasFactory;
    use HasFactory;

    protected $table = 'barang_masuk';
    protected $primaryKey = 'kode_masuk';
    protected $keyType = 'string'; // Tipe data primary key adalah string
    public $incrementing = false;
    protected $fillable = [
        'kode_masuk',
        'kode_sparepart',
        'jumlah',
        'tanggal_masuk',
    ];

    public function sparepart()
{
    return $this->belongsTo(Sparepart::class, 'kode_sparepart', 'kode_sparepart');
}

}
