<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\KomentarFoto;

class Foto extends Model
{
    protected $table = 'fotos';
    protected $primaryKey = 'id';
    public $timestamps = true;

    protected $fillable = [
        'JudulFoto',
        'DeskripsiFoto',
        'LokasiFile',
        'id_album',
        'id_user',
    ];

    // Relasi dengan model Album (Many-to-One)
    public function album()
    {
        return $this->belongsTo(Album::class, 'id_album', 'id');
    }

    // Relasi dengan model User (Many-to-One)
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id');
    }

    // Fungsi agar id_user yang memberikan like masuk ke dalam database
    public function isLikedByUser($userId)
    {
        return $this->likes()->where('id_user', $userId)->exists();
    }

    //Hitung jumlah like pada id_foto yang sama
    public function getTotalLikesAttribute()
    {
        return $this->likes()->count();
    }

    // Relasi dengan model KomentarFoto (Many-to-Many)
    public function komentar()
    {
        return $this->hasMany(KomentarFoto::class, 'id_foto');
    }

    // Relasi dengan model LikeFoto (Many-to-Many)
    public function likes()
    {
        return $this->hasMany(LikeFoto::class, 'id_foto');
    }
}
