<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PosManagement extends Model
{
    use HasFactory;

    protected $table = 'pos_management';
    protected $primaryKey = 'id_pos';

    protected $fillable = [
        'tipe', 'id_outlet', 'diskon', 'satuan_diskon',
        'tanggal_mulai', 'tanggal_akhir'
    ];

    public function outlet()
    {
        return $this->belongsTo(Outlet::class, 'id_outlet');
    }

    public function member()
    {
        return $this->belongsTo(Member::class, 'id_member');
    }
}
