<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Guru extends Model
{
    use HasFactory;

    // Tambahkan properti fillable
    protected $fillable = [
        'nama',
        'nip',
        'gender',
        'alamat',
        'kontak',
        'email',
    ];
    public function industris()
    {
        return $this->hasMany(Industri::class, 'guru_pembimbing');
    }
}
