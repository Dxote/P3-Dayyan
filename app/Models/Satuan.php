<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Satuan extends Model
{
    use HasFactory;

    protected $table = 'satuan';
    protected $primaryKey = 'kode_satuan';
    protected $keyType = 'string'; // Tipe data primary key adalah string
    public $incrementing = false;
    protected $fillable = [
        'kode_satuan',
        'nama_satuan',
    ];
    public function spare()
    {
        return $this->hasMany(SparePart::class, 'kode_sparepart', 'kode_sparepart');
    }
}
