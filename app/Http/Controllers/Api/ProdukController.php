<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProdukController extends Controller
{
    /**
     * Tampilkan daftar produk.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $dataProduk = [
            ["id" => 1, "nama" => "Sepatu Kets", "harga" => 150000, "stok" => 10],
            ["id" => 2, "nama" => "Tas Ransel", "harga" => 200000, "stok" => 5]
        ];

        return $this->sendResponse($dataProduk, 'Data produk berhasil diambil.');
    }
}