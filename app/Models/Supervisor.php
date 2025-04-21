<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\User;

class Supervisor extends Model
{
    use HasFactory;

    protected $table = 'supervisor';
    protected $primaryKey = 'id_supervisor';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = ['id_supervisor', 'id_outlet', 'id_user'];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }
    public function outlet()
    {
        return $this->belongsTo(Outlet::class, 'id_outlet');
    }
}
