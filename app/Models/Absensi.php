<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Absensi extends Model
{
    use HasFactory;

    protected $table = 'absensi';
    protected $primaryKey = 'kode_absen';
    public $incrementing = false; // Karena primary key adalah string

    protected $fillable = [
        'kode_absen', 'user_id', 'kode_shift', 'tanggal_absen', 'jam_absen', 'status'
    ];

    protected $casts = [
        'tanggal_absen' => 'date',
        'jam_absen' => 'string', // Ubah dari 'time' ke 'string'
    ];
    

    public function user()
    {
        return $this->belongsTo(\App\User::class, 'user_id');
    }

    public function shift()
    {
        return $this->belongsTo(\App\Models\Shift::class, 'kode_shift', 'kode_shift');
    }
}
