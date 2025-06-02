<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class SiswaApi extends Controller
{
    /**
     * Display a listing of the resource with pagination
     */
    public function index(Request $request)
    {
        try {
            $perPage = $request->get('per_page', 15);
            $search = $request->get('search');
            
            $query = Siswa::query();
            
            if ($search) {
                $query->where(function($q) use ($search) {
                    $q->where('nama', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%")
                      ->orWhere('nis', 'like', "%{$search}%");
                });
            }
            
            $siswas = $query->paginate($perPage);
            
            return response()->json([
                'success' => true,
                'data' => $siswas
            ], 200);
            
        } catch (\Exception $e) {
            Log::error('Error fetching siswa data: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengambil data siswa'
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage
     */
    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'user_id' => 'nullable|exists:users,id',
                'nama' => 'required|string|max:255',
                'email' => 'required|email|unique:siswas,email',
                'nis' => 'required|string|unique:siswas,nis|max:20',
                'gender' => 'required|in:L,P',
                'alamat' => 'required|string|max:500',
                'kontak' => 'required|string|max:20',
                'status_pkl' => 'required|boolean',
                'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ]);

            DB::beginTransaction();

            // Handle file upload
            if ($request->hasFile('foto')) {
                $file = $request->file('foto');
                $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $validatedData['foto'] = $file->storeAs('foto', $filename, 'public');
            }

            $siswa = Siswa::create($validatedData);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Siswa berhasil ditambahkan',
                'data' => $siswa
            ], 201);

        } catch (ValidationException $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Data tidak valid',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error creating siswa: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menambahkan siswa'
            ], 500);
        }
    }

    /**
     * Display the specified resource
     */
    public function show(Siswa $siswa)
    {
        try {
            return response()->json([
                'success' => true,
                'data' => $siswa
            ], 200);
        } catch (\Exception $e) {
            Log::error('Error showing siswa: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengambil data siswa'
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage
     */
    public function update(Request $request, Siswa $siswa)
    {
        try {
            $validatedData = $request->validate([
                'user_id' => 'nullable|exists:users,id',
                'nama' => 'required|string|max:255',
                'email' => 'required|email|unique:siswas,email,' . $siswa->id,
                'nis' => 'required|string|unique:siswas,nis,' . $siswa->id . '|max:20',
                'gender' => 'required|in:L,P',
                'alamat' => 'required|string|max:500',
                'kontak' => 'required|string|max:20',
                'status_pkl' => 'required|boolean',
                'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ]);

            DB::beginTransaction();

            // Handle file upload
            if ($request->hasFile('foto')) {
                // Delete old photo if exists
                if ($siswa->foto && Storage::disk('public')->exists($siswa->foto)) {
                    Storage::disk('public')->delete($siswa->foto);
                }
                
                $file = $request->file('foto');
                $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $validatedData['foto'] = $file->storeAs('foto', $filename, 'public');
            }

            $siswa->update($validatedData);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Siswa berhasil diperbarui',
                'data' => $siswa->fresh()
            ], 200);

        } catch (ValidationException $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Data tidak valid',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating siswa: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memperbarui siswa'
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage
     */
    public function destroy(Siswa $siswa)
    {
        try {
            DB::beginTransaction();

            // Delete photo if exists
            if ($siswa->foto && Storage::disk('public')->exists($siswa->foto)) {
                Storage::disk('public')->delete($siswa->foto);
            }

            $siswa->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Siswa berhasil dihapus'
            ], 200);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error deleting siswa: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menghapus siswa'
            ], 500);
        }
    }

    /**
     * Get siswa by status PKL
     */
    public function getByStatusPkl(Request $request)
    {
        try {
            $request->validate([
                'status_pkl' => 'required|boolean'
            ]);

            $siswas = Siswa::where('status_pkl', $request->status_pkl)->get();

            return response()->json([
                'success' => true,
                'data' => $siswas
            ], 200);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak valid',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Error getting siswa by status PKL: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengambil data siswa'
            ], 500);
        }
    }

    /**
     * Search siswa
     */
    public function search(Request $request)
    {
        try {
            $request->validate([
                'query' => 'required|string|min:2'
            ]);

            $query = $request->get('query');
            
            $siswas = Siswa::where(function($q) use ($query) {
                $q->where('nama', 'like', "%{$query}%")
                  ->orWhere('email', 'like', "%{$query}%")
                  ->orWhere('nis', 'like', "%{$query}%");
            })->limit(10)->get();

            return response()->json([
                'success' => true,
                'data' => $siswas
            ], 200);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak valid',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Error searching siswa: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mencari siswa'
            ], 500);
        }
    }
}