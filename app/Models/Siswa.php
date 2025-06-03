<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

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

        static::updated(function ($siswa) {
            // Cek apakah email yang diubah
            if ($siswa->isDirty('email')) {
                $oldEmail = $siswa->getOriginal('email');
                $newEmail = $siswa->email;
                
                // Jika siswa memiliki user_id, update berdasarkan relasi
                if ($siswa->user_id) {
                    $user = User::find($siswa->user_id);
                    if ($user) {
                        $user->update(['email' => $newEmail]);
                        Log::info("Email synced via user_id: User ID {$user->id} email updated from {$oldEmail} to {$newEmail}");
                    }
                } else {
                    // Fallback: cari user berdasarkan email lama
                    $user = User::where('email', $oldEmail)->first();
                    if ($user) {
                        $user->update(['email' => $newEmail]);
                        
                        // Optional: update user_id di siswa untuk relasi yang lebih baik
                        $siswa->update(['user_id' => $user->id]);
                        
                        Log::info("Email synced via email lookup: User ID {$user->id} email updated from {$oldEmail} to {$newEmail}");
                    }
                }
            }
        });
    }

}