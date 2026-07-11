<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    /**
     * Tampilkan daftar kategori.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $categories = Category::all();
        return $this->sendResponse($categories, 'Daftar kategori berhasil diambil.');
    }

    /**
     * Simpan kategori baru.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:255|unique:categories,nama',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validasi gagal.', $validator->errors(), 422);
        }

        $category = Category::create([
            'nama' => $request->nama,
        ]);

        return $this->sendResponse($category, 'Kategori berhasil ditambahkan.', 201);
    }

    /**
     * Tampilkan detail satu kategori.
     */
    public function show($id): JsonResponse
    {
        $category = Category::find($id);

        if (is_null($category)) {
            return $this->sendError('Kategori tidak ditemukan.');
        }

        return $this->sendResponse($category, 'Data kategori berhasil diambil.');
    }

    /**
     * Perbarui data kategori.
     */
    public function update(Request $request, $id): JsonResponse
    {
        $category = Category::find($id);

        if (is_null($category)) {
            return $this->sendError('Kategori tidak ditemukan.');
        }

        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:255|unique:categories,nama,' . $id,
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validasi gagal.', $validator->errors(), 422);
        }

        $category->update([
            'nama' => $request->nama,
        ]);

        return $this->sendResponse($category, 'Kategori berhasil diperbarui.');
    }

    /**
     * Hapus kategori.
     */
    public function destroy($id): JsonResponse
    {
        $category = Category::find($id);

        if (is_null($category)) {
            return $this->sendError('Kategori tidak ditemukan.');
        }

        $category->delete();

        return $this->sendResponse([], 'Kategori berhasil dihapus.');
    }
}
