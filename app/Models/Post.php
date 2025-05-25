<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'siswa_id',
        'judul',
        'isi',
        'gambar',
        'tanggal',
    ];

    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }
}
