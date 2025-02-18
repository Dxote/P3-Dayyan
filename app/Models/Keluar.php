<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Keluar extends Model
{
    use HasFactory;
    protected $table = 'barang_keluar';
    protected $primaryKey = 'kode_keluar';
    protected $keyType = 'string'; // Tipe data primary key adalah string
    public $incrementing = false;
    protected $fillable = [
        'kode_keluar',
        'kode_sparepart',
        'jumlah',
        'tanggal_keluar',
    ];

    public function sparepart()
    {
        return $this->belongsTo(SparePart::class, 'kode_sparepart', 'kode_sparepart');
    }
    
}
