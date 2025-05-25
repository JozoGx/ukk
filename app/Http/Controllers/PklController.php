<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Siswa;
use App\Models\Industri;
use App\Models\Post;
use App\Models\Pkl;
class PklController extends Controller
{
    public function index()
    {
        $industris = Industri::all();  // Ambil semua data industri
        $siswaSudahPKL = Siswa::where('status_pkl', true)->get();  // Siswa yang sudah PKL

        return view('pkl.index', compact('industris', 'siswaSudahPKL'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'siswa_id' => 'required|exists:siswas,id',
            'industri_id' => 'required|exists:industris,id',
            'mulai' => 'required|date',
            'selesai' => 'required|date|after:mulai',
        ]);

        // Menyimpan data PKL
        $pkl = Pkl::create([
            'siswa_id' => $validated['siswa_id'],
            'industri_id' => $validated['industri_id'],
            'mulai' => $validated['mulai'],
            'selesai' => $validated['selesai'],
        ]);

        // Update status_pkl siswa
        Siswa::where('id', $validated['siswa_id'])->update(['status_pkl' => true]);

        // Tambahkan log ke posts
        Post::create([
            'siswa_id' => $validated['siswa_id'],
            'judul' => 'Pendaftaran PKL Berhasil',
            'konten' => 'Siswa telah berhasil mendaftar PKL di industri ID: ' . $validated['industri_id'],
        ]);

        return redirect()->route('dashboard')->with('success', 'PKL berhasil didaftarkan');
    }

    public function updatePembimbing(Request $request, $id)
    {
        $request->validate([
            'guru_id' => 'required|exists:gurus,id'
        ]);

        $pkl = Pkl::findOrFail($id);
        $pkl->guru_id = $request->guru_id;
        $pkl->save();

        return back()->with('success', 'Guru pembimbing berhasil diperbarui');
    }

    public function showPosts()
    {
        $posts = Post::with('siswa')->latest()->get(); 
        return view('posts.index', compact('posts'));
    }
}
