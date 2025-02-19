<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\User;

class Shift extends Model
{
    use HasFactory;

    protected $table = 'shift';
    protected $primaryKey = 'kode_shift';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'kode_shift',
        'user_id',
        'tanggal_shift',
        'jam_mulai',
        'jam_selesai',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
