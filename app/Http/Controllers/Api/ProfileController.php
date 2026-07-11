<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
    /**
     * Tampilkan Profil User yang sedang login.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function show(Request $request): JsonResponse
    {
        return $this->sendResponse($request->user(), 'Detail profil berhasil diambil.');
    }

    /**
     * Update Data Profil (name, username, no_telepon, alamat).
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function update(Request $request): JsonResponse
    {
        $user = $request->user();

        $validator = Validator::make($request->all(), [
            'name'       => 'required|string|max:255',
            'username'   => 'nullable|string|max:255|unique:users,username,' . $user->id,
            'no_telepon' => 'nullable|string|max:15',
            'alamat'     => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validasi gagal.', $validator->errors(), 422);
        }

        $user->update([
            'name'       => $request->name,
            'username'   => $request->username,
            'no_telepon' => $request->no_telepon,
            'alamat'     => $request->alamat,
        ]);

        return $this->sendResponse($user, 'Profil berhasil diperbarui.');
    }

    /**
     * Ganti Password User.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function updatePassword(Request $request): JsonResponse
    {
        $user = $request->user();

        $validator = Validator::make($request->all(), [
            'old_password' => 'required|string',
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validasi gagal.', $validator->errors(), 422);
        }

        if (!Hash::check($request->old_password, $user->password)) {
            return $this->sendError('Password lama tidak cocok.', [], 400);
        }

        $user->update([
            'password' => Hash::make($request->new_password)
        ]);

        return $this->sendResponse(null, 'Password berhasil diperbarui.');
    }
}
