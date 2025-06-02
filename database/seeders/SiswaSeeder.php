<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Siswa;

class SiswaSeeder extends Seeder
{
    public function run(): void
    {
        $siswas = [
            [
                'nama' => 'Deku Midoriya',
                'nis' => '202301001',
                'gender' => 'L',
                'alamat' => 'Musutafu, Jepang',
                'kontak' => '08123456701',
                'email' => 'deku@siswa.com',
                'foto' => null,
                'status_pkl' => false,
                'user_id' => null,
            ],
            [
                'nama' => 'Naruto Uzumaki',
                'nis' => '202301002',
                'gender' => 'L',
                'alamat' => 'Konoha, Distrik Barat',
                'kontak' => '08123456702',
                'email' => 'naruto@siswa.com',
                'foto' => null,
                'status_pkl' => true,
                'user_id' => null,
            ],
            [
                'nama' => 'Asuna Yuuki',
                'nis' => '202301003',
                'gender' => 'P',
                'alamat' => 'Aincrad Tower',
                'kontak' => '08123456703',
                'email' => 'asuna@siswa.com',
                'foto' => null,
                'status_pkl' => false,
                'user_id' => null,
            ],
        ];

        foreach ($siswas as $siswa) {
            Siswa::create($siswa);
        }
    }
}
