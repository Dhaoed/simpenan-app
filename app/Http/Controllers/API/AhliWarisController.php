<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\AhliWaris;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AhliWarisController extends Controller
{
    public function index()
    {
        return response()->json(['status' => 'success', 'data' => AhliWaris::orderBy('created_at', 'desc')->get()]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'nullable|string|max:150',
            'tanggal' => 'nullable|date',
            'dokumen' => 'nullable|file|mimes:pdf|max:5120',
        ]);

        $filePath = null;
        $dokumenNama = null;

        if ($request->hasFile('dokumen')) {
            $file = $request->file('dokumen');
            $dokumenNama = $file->getClientOriginalName();
            $filePath = $file->store('arsip_ahli_waris', 'public');
        }

        $ahliWaris = AhliWaris::create(array_merge($request->all(), [
            'file_path' => $filePath,
            'dokumen_nama' => $dokumenNama
        ]));

        return response()->json(['status' => 'success', 'message' => 'Data Ahli Waris berhasil ditambahkan', 'data' => $ahliWaris], 201);
    }

    public function show($id)
    {
        $ahliWaris = AhliWaris::findOrFail($id);
        return response()->json(['status' => 'success', 'data' => $ahliWaris]);
    }

    public function update(Request $request, $id)
    {
        $ahliWaris = AhliWaris::findOrFail($id);

        if ($request->hasFile('dokumen')) {
            if ($ahliWaris->file_path) {
                Storage::disk('public')->delete($ahliWaris->file_path);
            }
            $file = $request->file('dokumen');
            $ahliWaris->dokumen_nama = $file->getClientOriginalName();
            $ahliWaris->file_path = $file->store('arsip_ahli_waris', 'public');
        }

        $ahliWaris->update($request->except('dokumen'));
        return response()->json(['status' => 'success', 'message' => 'Data Ahli Waris berhasil diperbarui', 'data' => $ahliWaris]);
    }

    public function destroy($id)
    {
        $ahliWaris = AhliWaris::findOrFail($id);
        if ($ahliWaris->file_path) {
            Storage::disk('public')->delete($ahliWaris->file_path);
        }
        $ahliWaris->delete();
        return response()->json(['status' => 'success', 'message' => 'Data Ahli Waris berhasil dihapus']);
    }
}