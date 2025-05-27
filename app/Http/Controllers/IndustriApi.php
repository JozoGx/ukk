<?php 

namespace App\Http\Controllers;

use App\Models\Industri;
use Illuminate\Http\Request;

class IndustriApi extends Controller
{
    public function index()
    {
        return Industri::all();
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string',
            'bidang_usaha' => 'required',
            'alamat' => 'required',
            'kontak' => 'required',
            'email' => 'required|email|unique:industris',
        ]);

        return Industri::create($request->all());
    }

    public function show(Industri $industri)
    {
        return $industri;
    }

    public function update(Request $request, Industri $industri)
    {
        $request->validate([
            'nama' => 'required|string',
            'bidang_usaha' => 'required',
            'alamat' => 'required',
            'kontak' => 'required',
            'email' => 'required|email|unique:industris,email,' . $industri->id,
        ]);

        $industri->update($request->all());
        return $industri;
    }

    public function destroy(Industri $industri)
    {
        $industri->delete();
        return response()->json(null, 204);
    }
}
