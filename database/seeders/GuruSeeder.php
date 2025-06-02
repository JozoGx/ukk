<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Guru;

class GuruSeeder extends Seeder
{
    public function run(): void
    {
        $gurus = [
            [
                'nama' => 'Kakashi Hatake',
                'nip' => '1986011001',
                'gender' => 'L',
                'alamat' => 'Konoha, Distrik Timur',
                'kontak' => '0811223344',
                'email' => 'kakashi@smk2depok.sch.id',
            ],
            [
                'nama' => 'Minato Namikaze',
                'nip' => '1975040302',
                'gender' => 'L',
                'alamat' => 'Konoha, Distrik Pusat',
                'kontak' => '0822334455',
                'email' => 'minato@smk2depok.sch.id',
            ],
            [
                'nama' => 'Yoruichi Shihouin',
                'nip' => '1988111503',
                'gender' => 'P',
                'alamat' => 'Karakura Town',
                'kontak' => '0833445566',
                'email' => 'yoruichi@smk2depok.sch.id',
            ],
        ];

        foreach ($gurus as $guru) {
            Guru::create($guru);
        }
    }
}
