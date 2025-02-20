<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceSparepart extends Model
{
    use HasFactory;

    protected $table = 'service_sparepart';
    protected $primaryKey = 'kode_service_sparepart';
    protected $keyType = 'string';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'kode_service_sparepart',
        'kode_service',
        'kode_sparepart',
    ];

    public function service()
    {
        return $this->belongsTo(Service::class, 'kode_service', 'kode_service');
    }

    public function sparepart()
    {
        return $this->belongsTo(SparePart::class, 'kode_sparepart', 'kode_sparepart');
    }
}
