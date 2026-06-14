<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\UangDuka;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UangDukaController extends Controller
{
    public function index()
    {
        return response()->json(['status' => 'success', 'data' => UangDuka::orderBy('created_at', 'desc')->get()]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'nullable|string|max:100',
            'tanggal_wafat' => 'nullable|date',
            'tanggal_terima' => 'nullable|date',
            'dokumen' => 'nullable|file|mimes:pdf|max:5120',
        ]);

        $filePath = null;
        $dokumenNama = null;

        if ($request->hasFile('dokumen')) {
            $file = $request->file('dokumen');
            $dokumenNama = $file->getClientOriginalName();
            $filePath = $file->store('arsip_uang_duka', 'public');
        }

        $uangDuka = UangDuka::create(array_merge($request->all(), [
            'file_path' => $filePath,
            'dokumen_nama' => $dokumenNama
        ]));

        return response()->json(['status' => 'success', 'message' => 'Data Uang Duka berhasil ditambahkan', 'data' => $uangDuka], 201);
    }

    public function show($id)
    {
        $uangDuka = UangDuka::findOrFail($id);
        return response()->json(['status' => 'success', 'data' => $uangDuka]);
    }

    public function update(Request $request, $id)
    {
        $uangDuka = UangDuka::findOrFail($id);

        if ($request->hasFile('dokumen')) {
            if ($uangDuka->file_path) {
                Storage::disk('public')->delete($uangDuka->file_path);
            }
            $file = $request->file('dokumen');
            $uangDuka->dokumen_nama = $file->getClientOriginalName();
            $uangDuka->file_path = $file->store('arsip_uang_duka', 'public');
        }

        $uangDuka->update($request->except('dokumen'));
        return response()->json(['status' => 'success', 'message' => 'Data Uang Duka berhasil diperbarui', 'data' => $uangDuka]);
    }

    public function destroy($id)
    {
        $uangDuka = UangDuka::findOrFail($id);
        if ($uangDuka->file_path) {
            Storage::disk('public')->delete($uangDuka->file_path);
        }
        $uangDuka->delete();
        return response()->json(['status' => 'success', 'message' => 'Data Uang Duka berhasil dihapus']);
    }
}