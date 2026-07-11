<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProdukController extends Controller
{
    /**
     * Tampilkan daftar produk.
     */
    public function index(): JsonResponse
    {
        // Mengambil semua produk beserta data kategori
        $produk = Product::with('category')->get();
        return $this->sendResponse($produk, 'Data produk berhasil diambil.');
    }

    /**
     * Simpan produk baru.
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'category_id' => 'required|exists:categories,id',
            'nama' => 'required|string|max:255',
            'harga' => 'required|integer|min:0',
            'stok' => 'required|integer|min:0',
            'detail' => 'nullable|string',
            'gambar' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validasi Error.', $validator->errors(), 422);
        }

        $produk = Product::create($request->all());
        return $this->sendResponse($produk, 'Produk berhasil ditambahkan.');
    }

    /**
     * Tampilkan detail satu produk.
     */
    public function show($id): JsonResponse
    {
        $produk = Product::with('category')->find($id);

        if (is_null($produk)) {
            return $this->sendError('Produk tidak ditemukan.');
        }

        return $this->sendResponse($produk, 'Data produk berhasil diambil.');
    }

    /**
     * Perbarui data produk.
     */
    public function update(Request $request, $id): JsonResponse
    {
        $produk = Product::find($id);

        if (is_null($produk)) {
            return $this->sendError('Produk tidak ditemukan.');
        }

        $validator = Validator::make($request->all(), [
            'category_id' => 'sometimes|exists:categories,id',
            'nama' => 'sometimes|string|max:255',
            'harga' => 'sometimes|integer|min:0',
            'stok' => 'sometimes|integer|min:0',
            'detail' => 'nullable|string',
            'gambar' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validasi Error.', $validator->errors(), 422);
        }

        $produk->update($request->all());

        return $this->sendResponse($produk, 'Produk berhasil diperbarui.');
    }

    /**
     * Hapus produk.
     */
    public function destroy($id): JsonResponse
    {
        $produk = Product::find($id);

        if (is_null($produk)) {
            return $this->sendError('Produk tidak ditemukan.');
        }

        $produk->delete();

        return $this->sendResponse([], 'Produk berhasil dihapus.');
    }
}