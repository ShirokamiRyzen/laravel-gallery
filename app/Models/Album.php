<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Foto;

class Album extends Model
{
    protected $table = 'albums';
    protected $primaryKey = 'id';
    public $timestamps = true;

    protected $fillable = [
        'NamaAlbum',
        'Deskripsi',
        'id_user',
    ];

    // Relasi dengan model User (Many-to-One)
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id')->select('id', 'NamaLengkap'); // Memuat kolom 'NamaLengkap' dari relasi user
    }

    // Relasi dengan model Foto (Many-to-One)
    public function fotos()
    {
        return $this->hasMany(Foto::class, 'id_album', 'id');
    }
}
