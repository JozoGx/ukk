<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function afterSave(): void
    {
        if ($this->record->roles->contains('name', 'Siswa')) {
            \App\Models\Siswa::updateOrCreate(
                ['email' => $this->record->email],
                [
                    'nama' => $this->record->name,
                    // Sisanya bisa disesuaikan jika diedit juga dari sini
                ]
            );
        } else {
            // Jika role siswa dihapus, hapus dari tabel siswa
            \App\Models\Siswa::where('email', $this->record->email)->delete();
        }
    }

}
