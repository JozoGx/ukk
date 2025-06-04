<?php

namespace App\Http\Controllers;

use App\Models\Industri;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class IndustriController extends Controller
{
    // Constructor dihapus karena tidak diperlukan di Laravel 12
    // Pengecekan auth akan dilakukan manual di setiap method

    public function index(Request $request)
    {
        // Check authentication
        if (!Auth::check()) {
            return redirect()->route('login');
        }
        
        $user = Auth::user();
        
        // Check permission - admin dan siswa bisa melihat daftar industri
        if (!$user->hasRole(['admin', 'siswa'])) {
            abort(403, 'Anda tidak memiliki akses untuk melihat data industri.');
        }
        
        // Base query
        $query = Industri::query();
        
        // Search functionality
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('nama', 'like', '%' . $searchTerm . '%')
                  ->orWhere('bidang_usaha', 'like', '%' . $searchTerm . '%')
                  ->orWhere('alamat', 'like', '%' . $searchTerm . '%')
                  ->orWhere('kontak', 'like', '%' . $searchTerm . '%')
                  ->orWhere('email', 'like', '%' . $searchTerm . '%');
            });
        }
        
        // Get per page value dengan validasi
        $perPage = $request->get('per_page', 10);
        if (!in_array($perPage, [5, 10, 20, 50])) {
            $perPage = 10;
        }
        
        // Order by dan paginate
        $industris = $query->orderBy('nama', 'asc')->paginate($perPage);
        
        // Append query parameters untuk pagination links
        $industris->appends($request->query());
        
        // Pass user role information to view
        $isAdmin = $user->hasRole('admin');
        
        return view('Industri.index', compact('industris', 'isAdmin'));
    }
    
    public function create()
    {
        // Check authentication
        if (!Auth::check()) {
            return redirect()->route('login');
        }
        
        $user = Auth::user();
        
        // Check permission - admin dan siswa bisa create
        if (!$user->hasRole(['admin', 'siswa'])) {
            abort(403, 'Anda tidak memiliki akses untuk menambah data industri.');
        }
        
        return view('Industri.create');
    }
    
    public function store(Request $request)
    {
        // Check authentication
        if (!Auth::check()) {
            return redirect()->route('login');
        }
        
        $user = Auth::user();
        
        // Check permission - admin dan siswa bisa create
        if (!$user->hasRole(['admin', 'siswa'])) {
            abort(403, 'Anda tidak memiliki akses untuk menambah data industri.');
        }

        // Validation rules
        $rules = [
            'nama' => 'required|string|max:255',
            'bidang_usaha' => 'required|string|max:255',
            'alamat' => 'required|string',
            'kontak' => 'required|string|max:20',
            'email' => 'required|email|max:255|unique:industris,email',
        ];
        
        // Custom validation messages
        $messages = [
            'nama.required' => 'Nama industri harus diisi.',
            'nama.string' => 'Nama industri harus berupa teks.',
            'nama.max' => 'Nama industri maksimal 255 karakter.',
            'bidang_usaha.required' => 'Bidang usaha harus diisi.',
            'bidang_usaha.string' => 'Bidang usaha harus berupa teks.',
            'bidang_usaha.max' => 'Bidang usaha maksimal 255 karakter.',
            'alamat.required' => 'Alamat harus diisi.',
            'alamat.string' => 'Alamat harus berupa teks.',
            'kontak.required' => 'Kontak harus diisi.',
            'kontak.string' => 'Kontak harus berupa teks.',
            'kontak.max' => 'Kontak maksimal 20 karakter.',
            'email.required' => 'Email harus diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.max' => 'Email maksimal 255 karakter.',
            'email.unique' => 'Email sudah digunakan oleh industri lain.',
        ];
        
        $validated = $request->validate($rules, $messages);
        
        try {
            Industri::create($validated);
            
            if ($request->has('create_another')) {
                return redirect()->route('industri.create')
                    ->with('success', 'Industri berhasil ditambahkan!');
            }
            
            return redirect()->route('industri.index')
                ->with('success', 'Industri berhasil ditambahkan!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat menyimpan data industri: ' . $e->getMessage())
                ->withInput();
        }
    }
    
    public function show(Industri $industri)
    {
        // Check authentication
        if (!Auth::check()) {
            return redirect()->route('login');
        }
        
        $user = Auth::user();
        
        // Check permission - admin dan siswa bisa view detail
        if (!$user->hasRole(['admin', 'siswa'])) {
            abort(403, 'Anda tidak memiliki akses untuk melihat detail industri.');
        }
        
        return view('Industri.show', compact('industri'));
    }
    
    public function edit(Industri $industri)
    {
        // Check authentication
        if (!Auth::check()) {
            return redirect()->route('login');
        }
        
        $user = Auth::user();
        
        // Only admin can edit - mengikuti pattern dari DashboardPklController
        if (!$user->hasRole('admin')) {
            abort(403, 'Hanya admin yang dapat mengedit data industri.');
        }
        
        return view('Industri.edit', compact('industri'));
    }
    
    public function update(Request $request, Industri $industri)
    {
        // Check authentication
        if (!Auth::check()) {
            return redirect()->route('login');
        }
        
        $user = Auth::user();
        
        // Only admin can update - mengikuti pattern dari DashboardPklController
        if (!$user->hasRole('admin')) {
            abort(403, 'Hanya admin yang dapat mengubah data industri.');
        }

        // Validation rules
        $rules = [
            'nama' => 'required|string|max:255',
            'bidang_usaha' => 'required|string|max:255',
            'alamat' => 'required|string',
            'kontak' => 'required|string|max:20',
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('industris', 'email')->ignore($industri->id)
            ],
        ];
        
        // Custom validation messages
        $messages = [
            'nama.required' => 'Nama industri harus diisi.',
            'nama.string' => 'Nama industri harus berupa teks.',
            'nama.max' => 'Nama industri maksimal 255 karakter.',
            'bidang_usaha.required' => 'Bidang usaha harus diisi.',
            'bidang_usaha.string' => 'Bidang usaha harus berupa teks.',
            'bidang_usaha.max' => 'Bidang usaha maksimal 255 karakter.',
            'alamat.required' => 'Alamat harus diisi.',
            'alamat.string' => 'Alamat harus berupa teks.',
            'kontak.required' => 'Kontak harus diisi.',
            'kontak.string' => 'Kontak harus berupa teks.',
            'kontak.max' => 'Kontak maksimal 20 karakter.',
            'email.required' => 'Email harus diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.max' => 'Email maksimal 255 karakter.',
            'email.unique' => 'Email sudah digunakan oleh industri lain.',
        ];
        
        $validated = $request->validate($rules, $messages);
        
        try {
            $industri->update($validated);
            
            return redirect()->route('industri.index')
                ->with('success', 'Industri berhasil diperbarui!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat memperbarui data industri: ' . $e->getMessage())
                ->withInput();
        }
    }
    
    public function destroy(Industri $industri)
    {
        // Check authentication
        if (!Auth::check()) {
            return redirect()->route('login');
        }
        
        $user = Auth::user();
        
        // Only admin can delete - mengikuti pattern dari DashboardPklController
        if (!$user->hasRole('admin')) {
            abort(403, 'Hanya admin yang dapat menghapus data industri.');
        }

        try {
            $namaIndustri = $industri->nama;
            $industri->delete();
            
            return redirect()->route('industri.index')
                ->with('success', "Industri {$namaIndustri} berhasil dihapus!");
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat menghapus data industri: ' . $e->getMessage());
        }
    }
    
    /**
     * Export Industri data (optional method)
     */
    public function export(Request $request)
    {
        // Check authentication
        if (!Auth::check()) {
            return redirect()->route('login');
        }
        
        $user = Auth::user();
        
        // Only admin can export - mengikuti pattern dari DashboardPklController
        if (!$user->hasRole('admin')) {
            abort(403, 'Hanya admin yang dapat mengekspor data industri.');
        }
        
        // Logic untuk export data industri ke Excel/PDF
        // Implementasi sesuai kebutuhan
    }
    
    /**
     * Get Industri statistics for dashboard
     */
    public function getStatistics()
    {
        // Check authentication
        if (!Auth::check()) {
            return response()->json(['error' => 'Unauthenticated'], 401);
        }
        
        $user = Auth::user();
        
        // Check permission for statistics
        if (!$user->hasRole(['admin', 'siswa'])) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        
        $total = Industri::count();
        $aktif = Industri::whereHas('pkls', function($query) {
            $query->where('mulai', '<=', now())
                  ->where('selesai', '>=', now());
        })->count();
        
        return response()->json([
            'total' => $total,
            'aktif' => $aktif,
        ]);
    }
}