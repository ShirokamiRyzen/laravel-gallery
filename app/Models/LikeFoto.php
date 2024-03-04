<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LikeFoto extends Model
{
    protected $table = 'likefotos';
    protected $primaryKey = 'id';
    public $timestamps = true;

    protected $fillable = [
        'id_foto',
        'id_user',
    ];

    // Relasi dengan model Foto (Many-to-Many)
    public function fotos()
    {
        return $this->belongsToMany(Foto::class, 'like_foto_user', 'id_likefoto', 'id_foto'); // Menyesuaikan nama kolom pivot
    }

    // Relasi dengan model User (Many-to-Many)
    public function users()
    {
        return $this->belongsToMany(User::class, 'like_foto_user', 'id_likefoto', 'id_user'); // Menyesuaikan nama kolom pivot
    }
}
