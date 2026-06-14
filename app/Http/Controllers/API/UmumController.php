<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Umum;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UmumController extends Controller
{
    public function index()
    {
        return response()->json(['status' => 'success', 'data' => Umum::orderBy('created_at', 'desc')->get()]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'nullable|string|max:100',
            'pengurusan' => 'nullable|string|max:100',
            'dokumen' => 'nullable|file|mimes:pdf|max:5120',
        ]);

        $filePath = null;
        $dokumenNama = null;

        if ($request->hasFile('dokumen')) {
            $file = $request->file('dokumen');
            $dokumenNama = $file->getClientOriginalName();
            $filePath = $file->store('arsip_umum', 'public');
        }

        $umum = Umum::create(array_merge($request->all(), [
            'file_path' => $filePath,
            'dokumen_nama' => $dokumenNama
        ]));

        return response()->json(['status' => 'success', 'message' => 'Data Umum berhasil ditambahkan', 'data' => $umum], 201);
    }

    public function show($id)
    {
        $umum = Umum::findOrFail($id);
        return response()->json(['status' => 'success', 'data' => $umum]);
    }

    public function update(Request $request, $id)
    {
        $umum = Umum::findOrFail($id);
        
        if ($request->hasFile('dokumen')) {
            if ($umum->file_path) {
                Storage::disk('public')->delete($umum->file_path);
            }
            $file = $request->file('dokumen');
            $umum->dokumen_nama = $file->getClientOriginalName();
            $umum->file_path = $file->store('arsip_umum', 'public');
        }

        $umum->update($request->except('dokumen'));
        return response()->json(['status' => 'success', 'message' => 'Data Umum berhasil diperbarui', 'data' => $umum]);
    }

    public function destroy($id)
    {
        $umum = Umum::findOrFail($id);
        if ($umum->file_path) {
            Storage::disk('public')->delete($umum->file_path);
        }
        $umum->delete();
        return response()->json(['status' => 'success', 'message' => 'Data Umum berhasil dihapus']);
    }
}