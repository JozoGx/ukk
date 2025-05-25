<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Resources\Pages\CreateRecord;
use App\Models\Siswa;
use Filament\Notifications\Notification;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;

    // Simpan data sementara
    public string $nis;
    public string $gender;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Ambil nilai NIS dan gender dari form, lalu hapus dari data user
        $this->nis = $data['nis'];
        $this->gender = $data['gender'];

        unset($data['nis'], $data['gender']); // Hindari error kolom tidak ada di tabel users

        return $data;
    }

    protected function afterCreate(): void
    {
        if ($this->record->roles->contains('name', 'siswa')) {
            Siswa::create([
                'user_id' => $this->record->id,
                'nama' => $this->record->name,
                'email' => $this->record->email,
                'nis' => $this->nis,
                'gender' => $this->gender,
                'alamat' => '-',  
                'kontak' => '-',   
                'status_pkl' => false,
            ]);
            
        }

        Notification::make()
            ->title('User dan Siswa berhasil dibuat')
            ->success()
            ->send();
    }
}
