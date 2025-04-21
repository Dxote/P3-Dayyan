<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\User;
class Member extends Model
{
    protected $table = 'member';
    protected $primaryKey = 'id_member';
    public $incrementing = true;

    protected $fillable = [
        'id_user', 'saldo'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id');
    }
}
