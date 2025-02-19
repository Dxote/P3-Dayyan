<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    protected $table = 'service';
    protected $primaryKey = 'kode_service';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'kode_service',
        'plat_nomor',
        'nama_motor',
        'kode_brand',
        'deskripsi_masalah',
        'sparepart',
        'alat',
        'user_id',
        'petugas_id'
    ];

    protected $casts = [
        'sparepart' => 'array',
        'alat' => 'array',
    ];

    // Relasi ke Brand
    public function brand()
    {
        return $this->belongsTo(Brand::class, 'kode_brand', 'kode_brand');
    }

    public function spareparts()
    {
        return $this->belongsToMany(Sparepart::class, 'service_sparepart', 'service_id', 'kode_sparepart');
    }


    // Relasi ke Pengguna
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    // Relasi ke Petugas
    public function petugas()
    {
        return $this->belongsTo(User::class, 'petugas_id', 'id');
    }
}
