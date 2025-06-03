<?php

namespace App\Actions\Fortify;

use App\Models\User;
use App\Models\Siswa;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Laravel\Jetstream\Jetstream;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param  array<string, string>  $input
     */
    public function create(array $input): User
    {
        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => $this->passwordRules(),
            'terms' => Jetstream::hasTermsAndPrivacyPolicyFeature() ? ['accepted', 'required'] : '',
        ])->validate();

        // Cek apakah email ada di tabel siswa menggunakan query builder standar
        $siswa = Siswa::where('email', $input['email'])->first();

        return DB::transaction(function () use ($input, $siswa) {
            // Jika siswa ditemukan dan sudah memiliki user, tolak registrasi
            if ($siswa && !is_null($siswa->user_id)) {
                throw new \Illuminate\Validation\ValidationException(
                    \Illuminate\Support\Facades\Validator::make([], []),
                    ['email' => ['Email ini sudah terdaftar oleh user lain.']]
                );
            }

            // Buat user baru
            $user = User::create([
                'name' => $input['name'],
                'email' => $input['email'],
                'password' => Hash::make($input['password']),
            ]);

            // Jika email ada di data siswa, assign role siswa dan update siswa
            if ($siswa) {
                // Assign role siswa (pastikan role 'siswa' sudah ada)
                $user->assignRole('siswa');

                // Update siswa dengan user_id
                $siswa->update(['user_id' => $user->id]);
            }
            // Jika email tidak ada di data siswa, user tidak memiliki role apapun

            return $user;
        });
    }
}