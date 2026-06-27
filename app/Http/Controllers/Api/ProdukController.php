<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProdukController extends Controller
{
    // Fungsi ini yang akan dipanggil oleh frontend
    public function index()
    {
        // Ini adalah contoh data statis. Nanti kamu bisa menggantinya agar mengambil dari database.
        $dataProduk = [
            ["id" => 1, "nama" => "Sepatu Kets", "harga" => 150000, "stok" => 10],
            ["id" => 2, "nama" => "Tas Ransel", "harga" => 200000, "stok" => 5]
        ];

        // Baris ini akan mengubah data di atas menjadi format JSON agar bisa dibaca oleh frontend HTML-mu
        return response()->json($dataProduk);
    }
}