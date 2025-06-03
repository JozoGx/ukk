<?php 

namespace App\Http\Controllers;

use App\Models\Pkl;
use Illuminate\Http\Request;
use Carbon\Carbon;

class PklApi extends Controller
{
    public function index()
    {
        return Pkl::with(['siswa', 'guru', 'industri'])->get();
    }

    public function store(Request $request)
    {
        $request->validate([
            'siswa_id' => 'required|exists:siswas,id',
            'industri_id' => 'required|exists:industris,id',
            'guru_id' => 'required|exists:gurus,id',
            'mulai' => 'required|date',
            'selesai' => 'required|date|after_or_equal:mulai',
        ]);

        // Validasi minimal 90 hari
        $mulai = Carbon::parse($request->mulai);
        $selesai = Carbon::parse($request->selesai);
        $durasi = $mulai->diffInDays($selesai);

        if ($durasi < 90) {
            return response()->json([
                'message' => 'Durasi PKL minimal 90 hari',
                'errors' => [
                    'selesai' => ['Tanggal selesai harus minimal 90 hari dari tanggal mulai']
                ]
            ], 422);
        }

        return Pkl::create($request->all());
    }

    public function show(Pkl $pkl)
    {
        return $pkl->load(['siswa', 'guru', 'industri']);
    }

    public function update(Request $request, Pkl $pkl)
    {
        $request->validate([
            'siswa_id' => 'required|exists:siswas,id',
            'industri_id' => 'required|exists:industris,id',
            'guru_id' => 'required|exists:gurus,id',
            'mulai' => 'required|date',
            'selesai' => 'required|date|after_or_equal:mulai',
        ]);

        // Validasi minimal 90 hari
        $mulai = Carbon::parse($request->mulai);
        $selesai = Carbon::parse($request->selesai);
        $durasi = $mulai->diffInDays($selesai);

        if ($durasi < 90) {
            return response()->json([
                'message' => 'Durasi PKL minimal 90 hari',
                'errors' => [
                    'selesai' => ['Tanggal selesai harus minimal 90 hari dari tanggal mulai']
                ]
            ], 422);
        }

        $pkl->update($request->all());
        return $pkl;
    }

    public function destroy(Pkl $pkl)
    {
        $pkl->delete();
        return response()->json(null, 204);
    }
}