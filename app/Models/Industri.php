<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Industri extends Model
{
    use HasFactory;

    // Menentukan atribut yang dapat diisi secara massal
    protected $fillable = [
        'nama',
        'bidang_usaha',
        'alamat',
        'kontak',
        'email',
    ];

    public function guru()
    {
        return $this->belongsTo(Guru::class, 'guru_pembimbing');
    }

}
