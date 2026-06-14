<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Kesehatan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class KesehatanController extends Controller
{
    public function index()
    {
        return response()->json(['status' => 'success', 'data' => Kesehatan::orderBy('created_at', 'desc')->get()]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'nullable|string|max:255',
            'pengurusan' => 'nullable|string|max:50',
            'dokumen' => 'nullable|file|mimes:pdf|max:5120',
        ]);

        $filePath = null;
        $dokumenNama = null;

        if ($request->hasFile('dokumen')) {
            $file = $request->file('dokumen');
            $dokumenNama = $file->getClientOriginalName();
            $filePath = $file->store('arsip_kesehatan', 'public');
        }

        $kesehatan = Kesehatan::create(array_merge($request->all(), [
            'file_path' => $filePath,
            'dokumen_nama' => $dokumenNama
        ]));

        return response()->json(['status' => 'success', 'message' => 'Data Kesehatan berhasil ditambahkan', 'data' => $kesehatan], 201);
    }

    public function show($id)
    {
        $kesehatan = Kesehatan::findOrFail($id);
        return response()->json(['status' => 'success', 'data' => $kesehatan]);
    }

    public function update(Request $request, $id)
    {
        $kesehatan = Kesehatan::findOrFail($id);

        if ($request->hasFile('dokumen')) {
            if ($kesehatan->file_path) {
                Storage::disk('public')->delete($kesehatan->file_path);
            }
            $file = $request->file('dokumen');
            $kesehatan->dokumen_nama = $file->getClientOriginalName();
            $kesehatan->file_path = $file->store('arsip_kesehatan', 'public');
        }

        $kesehatan->update($request->except('dokumen'));
        return response()->json(['status' => 'success', 'message' => 'Data Kesehatan berhasil diperbarui', 'data' => $kesehatan]);
    }

    public function destroy($id)
    {
        $kesehatan = Kesehatan::findOrFail($id);
        if ($kesehatan->file_path) {
            Storage::disk('public')->delete($kesehatan->file_path);
        }
        $kesehatan->delete();
        return response()->json(['status' => 'success', 'message' => 'Data Kesehatan berhasil dihapus']);
    }
}