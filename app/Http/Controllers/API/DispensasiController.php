<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Dispensasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DispensasiController extends Controller
{
    public function index()
    {
        return response()->json(['status' => 'success', 'data' => Dispensasi::orderBy('created_at', 'desc')->get()]);
    }

    public function store(Request $request)
    {
        // nama_istri di-set required karena aturan NOT NULL di database lama
        $request->validate([
            'nama_suami' => 'nullable|string|max:150',
            'nama_istri' => 'required|string|max:150', 
            'dokumen' => 'nullable|file|mimes:pdf|max:5120',
        ]);

        $filePath = null;
        $dokumenNama = null;

        if ($request->hasFile('dokumen')) {
            $file = $request->file('dokumen');
            $dokumenNama = $file->getClientOriginalName();
            $filePath = $file->store('arsip_dispensasi', 'public');
        }

        $dispensasi = Dispensasi::create(array_merge($request->all(), [
            'file_path' => $filePath,
            'dokumen_nama' => $dokumenNama
        ]));

        return response()->json(['status' => 'success', 'message' => 'Data Dispensasi Nikah berhasil ditambahkan', 'data' => $dispensasi], 201);
    }

    public function show($id)
    {
        $dispensasi = Dispensasi::findOrFail($id);
        return response()->json(['status' => 'success', 'data' => $dispensasi]);
    }

    public function update(Request $request, $id)
    {
        $dispensasi = Dispensasi::findOrFail($id);

        if ($request->hasFile('dokumen')) {
            if ($dispensasi->file_path) {
                Storage::disk('public')->delete($dispensasi->file_path);
            }
            $file = $request->file('dokumen');
            $dispensasi->dokumen_nama = $file->getClientOriginalName();
            $dispensasi->file_path = $file->store('arsip_dispensasi', 'public');
        }

        $dispensasi->update($request->except('dokumen'));
        return response()->json(['status' => 'success', 'message' => 'Data Dispensasi Nikah berhasil diperbarui', 'data' => $dispensasi]);
    }

    public function destroy($id)
    {
        $dispensasi = Dispensasi::findOrFail($id);
        if ($dispensasi->file_path) {
            Storage::disk('public')->delete($dispensasi->file_path);
        }
        $dispensasi->delete();
        return response()->json(['status' => 'success', 'message' => 'Data Dispensasi Nikah berhasil dihapus']);
    }
}