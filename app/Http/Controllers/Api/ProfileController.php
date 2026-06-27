<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
    /**
     * Tampilkan Profil User yang sedang login
     */
    public function show(Request $request)
    {
        // $request->user() mengambil data user yang terotentikasi berdasarkan token
        return response()->json([
            'success' => true,
            'message' => 'Detail profil berhasil diambil.',
            'data' => $request->user()
        ], 200);
    }

    /**
     * Update Data Profil (name, username, no_telepon, alamat)
     */
    public function update(Request $request)
    {
        $user = $request->user();

        // Validasi input
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            // Username harus unik kecuali untuk user itu sendiri
            'username' => 'nullable|string|max:255|unique:users,username,' . $user->id,
            'no_telepon' => 'nullable|string|max:15',
            'alamat' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal.',
                'errors' => $validator->errors()
            ], 422);
        }

        // Perbarui data user
        $user->update([
            'name' => $request->name,
            'username' => $request->username,
            'no_telepon' => $request->no_telepon,
            'alamat' => $request->alamat,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Profil berhasil diperbarui.',
            'data' => $user
        ], 200);
    }

    /**
     * Ganti Password User
     */
    public function updatePassword(Request $request)
    {
        $user = $request->user();

        // Validasi input password
        $validator = Validator::make($request->all(), [
            'old_password' => 'required|string',
            'new_password' => 'required|string|min:8|confirmed', // new_password_confirmation wajib dikirim di request
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal.',
                'errors' => $validator->errors()
            ], 422);
        }

        // Validasi kecocokan password lama
        if (!Hash::check($request->old_password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Password lama tidak cocok.'
            ], 400);
        }

        // Perbarui password (akan otomatis ter-hash oleh Laravel karena casts model)
        $user->update([
            'password' => Hash::make($request->new_password)
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Password berhasil diperbarui.'
        ], 200);
    }
}
