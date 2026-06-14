<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    public function index()
    {
        // Menyembunyikan password agar tidak bocor lewat respon API JSON
        $admins = Admin::orderBy('created_at', 'desc')->get()->makeHidden(['password']);
        return response()->json(['status' => 'success', 'data' => $admins]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'username' => 'required|string|max:250|unique:admins,username',
            'password' => 'required|string|min:6',
            'foto_profil' => 'nullable|image|mimes:jpeg,png,jpg|max:2048', // Maks 2MB gambar
        ]);

        $photoPath = null;

        if ($request->hasFile('foto_profil')) {
            $photoPath = $request->file('foto_profil')->store('profile_pictures', 'public');
        }

        $admin = Admin::create([
            'username' => $request->username,
            'password' => Hash::make($request->password), // Enkripsi Bcrypt Aman
            'profile_picture' => $photoPath
        ]);

        return response()->json(['status' => 'success', 'message' => 'Admin baru berhasil didaftarkan', 'data' => $admin->makeHidden(['password'])], 201);
    }

    public function show($id)
    {
        $admin = Admin::findOrFail($id)->makeHidden(['password']);
        return response()->json(['status' => 'success', 'data' => $admin]);
    }

    public function update(Request $request, $id)
    {
        $admin = Admin::findOrFail($id);

        $request->validate([
            'username' => 'required|string|max:250|unique:admins,username,' . $admin->id,
            'password' => 'nullable|string|min:6',
            'foto_profil' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($request->hasFile('foto_profil')) {
            if ($admin->profile_picture) {
                Storage::disk('public')->delete($admin->profile_picture);
            }
            $admin->profile_picture = $request->file('foto_profil')->store('profile_pictures', 'public');
        }

        $admin->username = $request->username;

        // Jika password diisi di form, lakukan enkripsi ulang. Jika kosong, biarkan yang lama.
        if ($request->filled('password')) {
            $admin->password = Hash::make($request->password);
        }

        $admin->save();

        return response()->json(['status' => 'success', 'message' => 'Data Admin berhasil diperbarui', 'data' => $admin->makeHidden(['password'])]);
    }

    public function destroy($id)
    {
        $admin = Admin::findOrFail($id);
        if ($admin->profile_picture) {
            Storage::disk('public')->delete($admin->profile_picture);
        }
        $admin->delete();
        return response()->json(['status' => 'success', 'message' => 'Akun Admin berhasil dihapus']);
    }
}