<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pendidikan extends Model
{
    use HasFactory;

    protected $table = 'pendidikan';

    protected $fillable = [
        'jenjang',
    ];

    public $timestamps = true;

    public function orangTuas()
    {
        return $this->hasMany(OrangTua::class, 'pendidikan_id');
    }
}
