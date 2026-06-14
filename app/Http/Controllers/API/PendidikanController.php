<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Pendidikan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PendidikanController extends Controller
{
    public function index()
    {
        $data = Pendidikan::orderBy('created_at', 'desc')->get();
        return response()->json([
            'status' => 'success',
            'data' => $data
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:50',
            'pengurusan' => 'required|string|max:100',
            'dokumen' => 'nullable|file|mimes:pdf|max:5120',
        ]);

        $filePath = null;
        $dokumenNama = null;

        if ($request->hasFile('dokumen')) {
            $file = $request->file('dokumen');
            $dokumenNama = $file->getClientOriginalName();
            $filePath = $file->store('arsip_pendidikan', 'public'); 
        }

        $pendidikan = Pendidikan::create([
            'no' => $request->no,
            'nama' => $request->nama,
            'pengurusan' => $request->pengurusan,
            'alamat' => $request->alamat,
            'tanggal' => $request->tanggal,
            'penanggung_jawab' => $request->penanggung_jawab,
            'file_path' => $filePath,
            'dokumen_nama' => $dokumenNama,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Arsip Pendidikan berhasil ditambahkan',
            'data' => $pendidikan
        ], 201);
    }
}