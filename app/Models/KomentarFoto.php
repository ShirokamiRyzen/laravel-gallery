<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KomentarFoto extends Model
{
    protected $table = 'komentarfotos';
    protected $primaryKey = 'id';
    public $timestamps = true;

    protected $fillable = [
        'id_foto',
        'id_user',
        'IsiKomentar',
    ];

    // Relasi dengan model Foto (Many-to-One)
    public function foto()
    {
        return $this->belongsTo(Foto::class, 'id_foto', 'id'); // Menyesuaikan FK dan PK
    }

    // Relasi dengan model User (Many-to-One)
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id'); // Menyesuaikan FK dan PK
    }
}
