<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Outlet extends Model
{
    use HasFactory;

    protected $table = 'outlet';
    protected $primaryKey = 'id_outlet';
    protected $fillable = ['nama', 'alamat', 'no_telp', 'id_layanan'];

    public function layanan()
    {
        return $this->belongsToMany(Layanan::class, 'outlet_layanan', 'id_outlet', 'id_layanan');

    }
    public function getLayananArrayAttribute()
    {
        return explode(',', $this->id_layanan);
    }
}
