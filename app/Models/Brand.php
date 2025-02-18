<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    use HasFactory;
    protected $table = 'brand';
    protected $primaryKey = 'kode_brand';
    protected $keyType = 'string'; // Tipe data primary key adalah string
    public $incrementing = false;
    protected $fillable = [
        'kode_brand',
        'brand',
    ];
    public function spare()
    {
        return $this->hasMany(SparePart::class, 'kode_sparepart', 'kode_sparepart');
    }

    public function spareparts()
    {
        return $this->hasMany(SparePart::class, 'kode_brand', 'kode_brand');
    }
}
