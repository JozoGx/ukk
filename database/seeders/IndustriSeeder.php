<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Industri;

class IndustriSeeder extends Seeder
{
    public function run(): void
    {
        Industri::create([
            'nama' => 'PT Teknologi Hebat',
            'bidang_usaha' => 'Software Development',
            'alamat' => 'Jl. IT Center No.1',
            'kontak' => '0811223344',
            'email' => 'info@teknologihebat.com',
            'guru_pembimbing' => 1, 
        ]);

        Industri::create([
            'nama' => 'CV Kreatif Mandiri',
            'bidang_usaha' => 'Desain Grafis',
            'alamat' => 'Jl. Kreatif No.5',
            'kontak' => '0822334455',
            'email' => 'admin@kreatifmandiri.com',
            'guru_pembimbing' => 2,
        ]);
    }
}
