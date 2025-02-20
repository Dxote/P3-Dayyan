<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\User;

class Service extends Model
{
    use HasFactory;

    protected $table = 'service';
    protected $primaryKey = 'kode_service';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'kode_service',
        'plat_nomor',
        'nama_motor',
        'kode_brand',
        'deskripsi_masalah',
        'user_id',
        'petugas_id',
    ];

    // Relasi ke User (Pengguna)
    public function pengguna()
    {
        return $this->belongsTo(User::class, 'user_id', 'id')->where('level', 'pengguna');
    }

    // Relasi ke User (Petugas)
    public function petugas()
    {
        return $this->belongsTo(User::class, 'petugas_id', 'id')->where('level', 'petugas');
    }

    // Relasi ke Brand
    public function brand()
    {
        return $this->belongsTo(Brand::class, 'kode_brand', 'kode_brand');
    }

    // Relasi ke Sparepart melalui pivot service_sparepart
    public function serviceSpareparts()
    {
        return $this->hasMany(ServiceSparepart::class, 'kode_service', 'kode_service');
    }

    // Relasi ke Alat melalui pivot service_alat
    public function serviceAlat()
    {
        return $this->hasMany(ServiceAlat::class, 'kode_service', 'kode_service');
    }
}
