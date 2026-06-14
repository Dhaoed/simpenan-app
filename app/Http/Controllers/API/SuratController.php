<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Surat;
use Illuminate\Http\Request;

class SuratController extends Controller
{
    public function index()
    {
        return response()->json(['status' => 'success', 'data' => Surat::orderBy('created_at', 'desc')->get()]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'jenis_surat' => 'nullable|string|max:255',
            'status_surat' => 'nullable|string|max:255',
            'tanggal' => 'nullable|date',
        ]);

        $surat = Surat::create($request->all());
        return response()->json(['status' => 'success', 'message' => 'Data Surat berhasil ditambahkan', 'data' => $surat], 201);
    }

    public function show($id)
    {
        $surat = Surat::findOrFail($id);
        return response()->json(['status' => 'success', 'data' => $surat]);
    }

    public function update(Request $request, $id)
    {
        $surat = Surat::findOrFail($id);
        $surat->update($request->all());
        return response()->json(['status' => 'success', 'message' => 'Data Surat berhasil diperbarui', 'data' => $surat]);
    }

    public function destroy($id)
    {
        $surat = Surat::findOrFail($id);
        $surat->delete();
        return response()->json(['status' => 'success', 'message' => 'Data Surat berhasil dihapus']);
    }
}