<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Siswa extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'user_id',
        'nama',
        'email',
        'nis',
        'gender',
        'alamat',
        'kontak',
        'status_pkl',
    ];
    
    
    protected $casts = [
        'status_pkl' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function pkl()
    {
        return $this->hasOne(Pkl::class); // atau ->hasMany(...) jika lebih dari 1
    }

}