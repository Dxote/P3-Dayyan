<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceAlat extends Model
{
    use HasFactory;

    protected $table = 'service_alat';
    protected $primaryKey = 'kode_service_alat';
    protected $keyType = 'string';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'kode_service_alat',
        'kode_service',
        'kode_alat',
    ];

    public function service()
    {
        return $this->belongsTo(Service::class, 'kode_service', 'kode_service');
    }

    public function alat()
    {
        return $this->belongsTo(Alat::class, 'kode_alat', 'kode_alat');
    }
}
