<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

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
        'foto',
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
        return $this->hasOne(Pkl::class); 
    }

    public function getFotoUrlAttribute()
    {
        if ($this->foto) {
            return Storage::url($this->foto);
        }
        
        return asset('images/default-avatar.png');
    }
    protected static function boot()
    {
        parent::boot();

        static::updating(function ($siswa) {
            if ($siswa->isDirty('foto') && $siswa->getOriginal('foto')) {
                Storage::delete($siswa->getOriginal('foto'));
            }
        });

        static::deleting(function ($siswa) {
            if ($siswa->foto) {
                Storage::delete($siswa->foto);
            }
        });
    }

}