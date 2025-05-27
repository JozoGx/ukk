<?php 

namespace App\Http\Controllers;

use App\Models\Pkl;
use Illuminate\Http\Request;

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

        $pkl->update($request->all());
        return $pkl;
    }

    public function destroy(Pkl $pkl)
    {
        $pkl->delete();
        return response()->json(null, 204);
    }
}
