<?php

namespace App\Http\Controllers;

use App\Models\Pkl;
use App\Models\Siswa;
use App\Models\Industri;
use App\Models\Guru;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardPklController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $pkls = Pkl::with(['siswa', 'industri', 'guru'])->latest()->get();

        $hasPkl = false;
        if ($user->hasRole('siswa')) {
            $hasPkl = Pkl::whereHas('siswa', function($query) use ($user) {
                $query->where('user_id', $user->id);
            })->exists();
        }

        $isAdmin = $user->hasRole('admin');

        return view('dashboard-pkls', compact('pkls', 'isAdmin', 'hasPkl'));
    }
    
    public function create()
    {
        $user = Auth::user();
        
        // Check if user is siswa and already has PKL
        if (!$user->hasRole('admin')) {
            $hasPkl = Pkl::whereHas('siswa', function($query) use ($user) {
                $query->where('user_id', $user->id);
            })->exists();
            
            if ($hasPkl) {
                return redirect()->route('dashboard.pkls.index')
                    ->with('error', 'Anda sudah memiliki data PKL.');
            }
        }
        
        $siswas = Siswa::all();
        $industris = Industri::all();
        $gurus = Guru::all();
        
        return view('create-pkl', compact('siswas', 'industris', 'gurus'));
    }
    
    public function store(Request $request)
    {
        $user = Auth::user();
        
        // Validation rules
        $rules = [
            'siswa_id' => 'required|exists:siswas,id',
            'industri_id' => 'required|exists:industris,id',
            'guru_id' => 'required|exists:gurus,id',
            'mulai' => 'required|date|after_or_equal:today',
            'selesai' => 'required|date|after:mulai'
        ];
        
        // Additional validation for non-admin users
        if (!$user->hasRole('admin')) {
            // Check if user already has PKL
            $hasPkl = Pkl::whereHas('siswa', function($query) use ($user) {
                $query->where('user_id', $user->id);
            })->exists();
            
            if ($hasPkl) {
                return redirect()->back()
                    ->with('error', 'Anda sudah memiliki data PKL.');
            }
            
            // Ensure siswa_id matches current user's siswa record
            $siswa = Siswa::where('user_id', $user->id)->first();
            if (!$siswa || $request->siswa_id != $siswa->id) {
                return redirect()->back()
                    ->with('error', 'Anda hanya dapat mendaftarkan PKL untuk diri sendiri.');
            }
        }
        
        $validated = $request->validate($rules);
        
        try {
            Pkl::create($validated);
            
            return redirect()->route('dashboard.pkls.index')
                ->with('success', 'Data PKL berhasil dibuat.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat menyimpan data PKL.')
                ->withInput();
        }
    }
    
    public function show(Pkl $pkl)
    {
        $user = Auth::user();
        
        // Check permissions
        // if (!$user->hasRole('admin')) {
        //     $userSiswa = Siswa::where('user_id', $user->id)->first();
        //     if (!$userSiswa || $pkl->siswa_id !== $userSiswa->id) {
        //         abort(403, 'Unauthorized access.');
        //     }
        // }
        
        $pkl->load(['siswa', 'industri', 'guru']);
        
        return view('show-pkl', compact('pkl'));
    }
    
    public function edit(Pkl $pkl)
    {
        $user = Auth::user();
        
        // Only admin can edit
        if (!$user->hasRole('admin')) {
            abort(403, 'Unauthorized access.');
        }
        
        $siswas = Siswa::all();
        $industris = Industri::all();
        $gurus = Guru::all();
        
        return view('edit-pkl', compact('pkl', 'siswas', 'industris', 'gurus'));
    }
    
    public function update(Request $request, Pkl $pkl)
    {
        $user = Auth::user();
        
        // Only admin can update
        if (!$user->hasRole('admin')) {
            abort(403, 'Unauthorized access.');
        }
        
        $validated = $request->validate([
            'siswa_id' => 'required|exists:siswas,id',
            'industri_id' => 'required|exists:industris,id',
            'guru_id' => 'required|exists:gurus,id',
            'mulai' => 'required|date',
            'selesai' => 'required|date|after:mulai'
        ]);
        
        try {
            $pkl->update($validated);
            
            return redirect()->route('dashboard.pkls.index')
                ->with('success', 'Data PKL berhasil diperbarui.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat memperbarui data PKL.')
                ->withInput();
        }
    }
    
    public function destroy(Pkl $pkl)
    {
        $user = Auth::user();
        
        // Only admin can delete
        if (!$user->hasRole('admin')) {
            abort(403, 'Unauthorized access.');
        }
        
        try {
            $pkl->delete();
            
            return redirect()->route('dashboard.pkls.index')
                ->with('success', 'Data PKL berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat menghapus data PKL.');
        }
    }
}