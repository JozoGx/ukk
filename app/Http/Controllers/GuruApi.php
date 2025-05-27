<?php 

namespace App\Http\Controllers;

use App\Models\Guru;
use Illuminate\Http\Request;

class GuruApi extends Controller
{
    public function index()
    {
        return Guru::all();
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string',
            'nip' => 'required|unique:gurus',
            'gender' => 'required',
            'alamat' => 'required',
            'kontak' => 'required',
            'email' => 'required|email|unique:gurus',
        ]);

        return Guru::create($request->all());
    }

    public function show(Guru $guru)
    {
        return $guru;
    }

    public function update(Request $request, Guru $guru)
    {
        $request->validate([
            'nama' => 'required|string',
            'nip' => 'required|unique:gurus,nip,' . $guru->id,
            'gender' => 'required',
            'alamat' => 'required',
            'kontak' => 'required',
            'email' => 'required|email|unique:gurus,email,' . $guru->id,
        ]);

        $guru->update($request->all());
        return $guru;
    }

    public function destroy(Guru $guru)
    {
        $guru->delete();
        return response()->json(null, 204);
    }
}
