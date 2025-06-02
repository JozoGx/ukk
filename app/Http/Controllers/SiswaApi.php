<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SiswaApi extends Controller
{
    public function index()
    {
        $siswas = Siswa::all();
        return response()->json([
            'success' => true,
            'data' => $siswas
        ], 200);
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'user_id' => 'nullable|exists:users,id',
                'nama' => 'required|string|max:255',
                'email' => 'required|email|unique:siswas,email',
                'nis' => 'required|string|unique:siswas,nis',
                'gender' => 'required|in:L,P',
                'alamat' => 'required|string',
                'kontak' => 'required|string',
                'status_pkl' => 'required|in:true,false,1,0',
                'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            $data = $request->all();

            if (isset($data['status_pkl'])) {
                $data['status_pkl'] = filter_var($data['status_pkl'], FILTER_VALIDATE_BOOLEAN);
            }

            if ($request->hasFile('foto')) {
                $file = $request->file('foto');
                $filename = time() . '_' . $file->getClientOriginalName();
                $data['foto'] = $file->storeAs('foto', $filename, 'public');
            }

            $siswa = Siswa::create($data);

            return response()->json([
                'success' => true,
                'message' => 'Siswa berhasil ditambahkan',
                'data' => $siswa
            ], 201);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function show(Siswa $siswa)
    {
        return response()->json([
            'success' => true,
            'data' => $siswa
        ], 200);
    }

    public function update(Request $request, Siswa $siswa)
    {
        try {
            $request->validate([
                'user_id' => 'nullable|exists:users,id',
                'nama' => 'required|string|max:255',
                'email' => 'required|email|unique:siswas,email,' . $siswa->id,
                'nis' => 'required|string|unique:siswas,nis,' . $siswa->id,
                'gender' => 'required|in:L,P',
                'alamat' => 'required|string',
                'kontak' => 'required|string',
                'status_pkl' => 'required|in:true,false,1,0',
                'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            $data = $request->all();

            
            if ($request->hasFile('foto')) {
                
                if ($siswa->foto && Storage::disk('public')->exists($siswa->foto)) {
                    Storage::disk('public')->delete($siswa->foto);
                }
                
                $file = $request->file('foto');
                $filename = time() . '_' . $file->getClientOriginalName();
                $data['foto'] = $file->storeAs('foto', $filename, 'public');
            }

            $siswa->update($data);

            return response()->json([
                'success' => true,
                'message' => 'Siswa berhasil diupdate',
                'data' => $siswa
            ], 200);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy(Siswa $siswa)
    {
        try {
            
            if ($siswa->foto && Storage::disk('public')->exists($siswa->foto)) {
                Storage::disk('public')->delete($siswa->foto);
            }

            $siswa->delete();

            return response()->json([
                'success' => true,
                'message' => 'Siswa berhasil dihapus'
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
}