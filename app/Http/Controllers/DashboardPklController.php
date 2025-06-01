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
    public function index(Request $request)
    {
        $user = Auth::user();
        $isAdmin = $user->hasRole('admin');

        // Base query dengan eager loading
        $query = Pkl::with(['siswa', 'industri', 'guru']);

        // Filter berdasarkan role user - HANYA untuk guru, tidak untuk siswa
        if (!$isAdmin) {
            if ($user->hasRole('guru')) {
                $query->whereHas('guru', function($q) use ($user) {
                    $q->where('user_id', $user->id);
                });
            }
            // Siswa dapat melihat semua data PKL, tidak perlu filter
        }

        // Search functionality
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->whereHas('siswa', function($query) use ($searchTerm) {
                    $query->where('nama', 'like', '%' . $searchTerm . '%');
                })
                ->orWhereHas('industri', function($query) use ($searchTerm) {
                    $query->where('nama', 'like', '%' . $searchTerm . '%');
                })
                ->orWhereHas('guru', function($query) use ($searchTerm) {
                    $query->where('nama', 'like', '%' . $searchTerm . '%');
                });
            });
        }

        // Get per page value dengan validasi
        $perPage = $request->get('per_page', 10);
        if (!in_array($perPage, [5, 10, 20, 50])) {
            $perPage = 10;
        }

        // Order by dan paginate
        $pkls = $query->orderBy('created_at', 'desc')->paginate($perPage);
        
        // Append query parameters untuk pagination links
        $pkls->appends($request->query());

        // Calculate statistics - gunakan query terpisah untuk akurasi
        $statsQuery = Pkl::query();
        if (!$isAdmin) {
            if ($user->hasRole('guru')) {
                $statsQuery->whereHas('guru', function($q) use ($user) {
                    $q->where('user_id', $user->id);
                });
            }
            // Siswa dapat melihat statistik semua PKL
        }

        $totalPkls = $statsQuery->count();
        $pklAktif = $statsQuery->where('mulai', '<=', now())
                              ->where('selesai', '>=', now())
                              ->count();
        $pklSelesai = $statsQuery->where('selesai', '<', now())->count();

        // Check if user has PKL data - tetap untuk kontrol tombol create
        $hasPkl = false;
        if ($user->hasRole('siswa')) {
            $hasPkl = Pkl::whereHas('siswa', function($query) use ($user) {
                $query->where('user_id', $user->id);
            })->exists();
        }

        return view('dashboard-pkls', compact(
            'pkls', 
            'isAdmin', 
            'hasPkl',
            'totalPkls',
            'pklAktif',
            'pklSelesai'
        ));
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
        
        // Filter data berdasarkan role
        if ($user->hasRole('admin')) {
            $siswas = Siswa::orderBy('nama')->get();
            $industris = Industri::orderBy('nama')->get();
            $gurus = Guru::orderBy('nama')->get();
        } else {
            // Jika siswa, hanya tampilkan data dirinya
            if ($user->hasRole('siswa')) {
                $siswas = Siswa::where('user_id', $user->id)->get();
            } else {
                $siswas = Siswa::orderBy('nama')->get();
            }
            $industris = Industri::orderBy('nama')->get();
            $gurus = Guru::orderBy('nama')->get();
        }
        
        return view('create-pkl', compact('siswas', 'industris', 'gurus'));
    }
    
    // store method untuk menyimpan data PKL baru
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
        
        // Check if user is not admin
        if (!$user->hasRole('admin')) {
            // Check if user already has PKL
            $hasPkl = Pkl::whereHas('siswa', function($query) use ($user) {
                $query->where('user_id', $user->id);
            })->exists();
            
            if ($hasPkl) {
                return redirect()->back()
                    ->with('error', 'Anda sudah memiliki data PKL.')
                    ->withInput();
            }
            
            // Ensure siswa_id matches current user's siswa record
            if ($user->hasRole('siswa')) {
                $siswa = Siswa::where('user_id', $user->id)->first();
                if (!$siswa || $request->siswa_id != $siswa->id) {
                    return redirect()->back()
                        ->with('error', 'Anda hanya dapat mendaftarkan PKL untuk diri sendiri.')
                        ->withInput();
                }
            }
        }
        
        // Custom validation messages
        $messages = [
            'siswa_id.required' => 'Siswa harus dipilih.',
            'siswa_id.exists' => 'Data siswa tidak valid.',
            'industri_id.required' => 'Industri harus dipilih.',
            'industri_id.exists' => 'Data industri tidak valid.',
            'guru_id.required' => 'Guru pembimbing harus dipilih.',
            'guru_id.exists' => 'Data guru tidak valid.',
            'mulai.required' => 'Tanggal mulai harus diisi.',
            'mulai.date' => 'Format tanggal mulai tidak valid.',
            'mulai.after_or_equal' => 'Tanggal mulai tidak boleh kurang dari hari ini.',
            'selesai.required' => 'Tanggal selesai harus diisi.',
            'selesai.date' => 'Format tanggal selesai tidak valid.',
            'selesai.after' => 'Tanggal selesai harus setelah tanggal mulai.'
        ];
        
        $validated = $request->validate($rules, $messages);
        
        try {
            // Check for conflicting schedules
            $conflictExists = Pkl::where('siswa_id', $validated['siswa_id'])
                ->where(function($query) use ($validated) {
                    $query->whereBetween('mulai', [$validated['mulai'], $validated['selesai']])
                          ->orWhereBetween('selesai', [$validated['mulai'], $validated['selesai']])
                          ->orWhere(function($q) use ($validated) {
                              $q->where('mulai', '<=', $validated['mulai'])
                                ->where('selesai', '>=', $validated['selesai']);
                          });
                })
                ->exists();
            
            if ($conflictExists) {
                return redirect()->back()
                    ->with('error', 'Terdapat jadwal PKL yang bertabrakan untuk siswa ini.')
                    ->withInput();
            }
            
            Pkl::create($validated);
            
            return redirect()->route('dashboard.pkls.index')
                ->with('success', 'Data PKL berhasil dibuat.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat menyimpan data PKL: ' . $e->getMessage())
                ->withInput();
        }
    }
    
    public function show(Pkl $pkl)
    {
        $user = Auth::user();
        
        // Check permissions 
        if (!$user->hasRole('admin')) {
            if ($user->hasRole('guru')) {
                $userGuru = Guru::where('user_id', $user->id)->first();
                if (!$userGuru || $pkl->guru_id !== $userGuru->id) {
                    abort(403, 'Anda hanya dapat melihat PKL yang Anda bimbing.');
                }
            }
        }
        
        $pkl->load(['siswa', 'industri', 'guru']);
        
        return view('show-pkl', compact('pkl'));
    }
    
    public function edit(Pkl $pkl)
    {
        $user = Auth::user();
        
        // Only admin can edit
        if (!$user->hasRole('admin')) {
            abort(403, 'Hanya admin yang dapat mengedit data PKL.');
        }
        
        $siswas = Siswa::orderBy('nama')->get();
        $industris = Industri::orderBy('nama')->get();
        $gurus = Guru::orderBy('nama')->get();
        
        return view('edit-pkl', compact('pkl', 'siswas', 'industris', 'gurus'));
    }
    
    public function update(Request $request, Pkl $pkl)
    {
        $user = Auth::user();
        
        // Only admin can update
        if (!$user->hasRole('admin')) {
            abort(403, 'Hanya admin yang dapat mengubah data PKL.');
        }
        
        $rules = [
            'siswa_id' => 'required|exists:siswas,id',
            'industri_id' => 'required|exists:industris,id',
            'guru_id' => 'required|exists:gurus,id',
            'mulai' => 'required|date',
            'selesai' => 'required|date|after:mulai'
        ];
        
        $messages = [
            'siswa_id.required' => 'Siswa harus dipilih.',
            'siswa_id.exists' => 'Data siswa tidak valid.',
            'industri_id.required' => 'Industri harus dipilih.',
            'industri_id.exists' => 'Data industri tidak valid.',
            'guru_id.required' => 'Guru pembimbing harus dipilih.',
            'guru_id.exists' => 'Data guru tidak valid.',
            'mulai.required' => 'Tanggal mulai harus diisi.',
            'mulai.date' => 'Format tanggal mulai tidak valid.',
            'selesai.required' => 'Tanggal selesai harus diisi.',
            'selesai.date' => 'Format tanggal selesai tidak valid.',
            'selesai.after' => 'Tanggal selesai harus setelah tanggal mulai.'
        ];
        
        $validated = $request->validate($rules, $messages);
        
        try {
            // Check for conflicting schedules (exclude current PKL)
            $conflictExists = Pkl::where('siswa_id', $validated['siswa_id'])
                ->where('id', '!=', $pkl->id)
                ->where(function($query) use ($validated) {
                    $query->whereBetween('mulai', [$validated['mulai'], $validated['selesai']])
                          ->orWhereBetween('selesai', [$validated['mulai'], $validated['selesai']])
                          ->orWhere(function($q) use ($validated) {
                              $q->where('mulai', '<=', $validated['mulai'])
                                ->where('selesai', '>=', $validated['selesai']);
                          });
                })
                ->exists();
            
            if ($conflictExists) {
                return redirect()->back()
                    ->with('error', 'Terdapat jadwal PKL yang bertabrakan untuk siswa ini.')
                    ->withInput();
            }
            
            $pkl->update($validated);
            
            return redirect()->route('dashboard.pkls.index')
                ->with('success', 'Data PKL berhasil diperbarui.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat memperbarui data PKL: ' . $e->getMessage())
                ->withInput();
        }
    }
    
    public function destroy(Pkl $pkl)
    {
        $user = Auth::user();
        
        // Only admin can delete
        if (!$user->hasRole('admin')) {
            abort(403, 'Hanya admin yang dapat menghapus data PKL.');
        }
        
        try {
            $siswaName = $pkl->siswa->nama;
            $pkl->delete();
            
            return redirect()->route('dashboard.pkls.index')
                ->with('success', "Data PKL untuk siswa {$siswaName} berhasil dihapus.");
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat menghapus data PKL: ' . $e->getMessage());
        }
    }

    /**
     * Export PKL data 
     */
    public function export(Request $request)
    {
        $user = Auth::user();
        
        if (!$user->hasRole('admin')) {
            abort(403, 'Hanya admin yang dapat mengekspor data.');
        }
        
        // Logic untuk export data PKL ke Excel/PDF
        // Implementasi sesuai kebutuhan
    }

    /**
     * Get PKL statistics for dashboard
     */
    public function getStatistics()
    {
        $user = Auth::user();
        $isAdmin = $user->hasRole('admin');

        $query = Pkl::query();
        
        if (!$isAdmin) {
            if ($user->hasRole('guru')) {
                $query->whereHas('guru', function($q) use ($user) {
                    $q->where('user_id', $user->id);
                });
            }
            // Siswa dapat melihat statistik semua PKL
        }

        $total = $query->count();
        $aktif = $query->where('mulai', '<=', now())
                      ->where('selesai', '>=', now())
                      ->count();
        $selesai = $query->where('selesai', '<', now())->count();
        $belumMulai = $query->where('mulai', '>', now())->count();

        return response()->json([
            'total' => $total,
            'aktif' => $aktif,
            'selesai' => $selesai,
            'belum_mulai' => $belumMulai
        ]);
    }
}