<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use Illuminate\Http\Request;

class SiswaApi extends Controller
{
    public function index()
    {
        return Siswa::all();
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'nama' => 'required',
            'email' => 'required|email|unique:siswas',
            'nis' => 'required|unique:siswas',
            'gender' => 'required',
            'alamat' => 'required',
            'kontak' => 'required',
            'status_pkl' => 'required|in:belum,sedang,sudah',
        ]);

        return Siswa::create($request->all());
    }

    public function show(Siswa $siswa)
    {
        return $siswa;
    }

    public function update(Request $request, Siswa $siswa)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'nama' => 'required',
            'email' => 'required|email|unique:siswas,email,' . $siswa->id,
            'nis' => 'required|unique:siswas,nis,' . $siswa->id,
            'gender' => 'required',
            'alamat' => 'required',
            'kontak' => 'required',
            'status_pkl' => 'required|in:belum,sedang,sudah',
        ]);

        $siswa->update($request->all());
        return $siswa;
    }

    public function destroy(Siswa $siswa)
    {
        $siswa->delete();
        return response()->json(null, 204);
    }
}
