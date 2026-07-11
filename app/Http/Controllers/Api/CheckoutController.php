<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class CheckoutController extends Controller
{
    /**
     * Memproses checkout pesanan.
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'alamat_pengiriman' => 'required|string',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.jumlah' => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validasi gagal.', $validator->errors(), 422);
        }

        try {
            DB::beginTransaction();

            $totalHarga = 0;
            $user = $request->user();

            // Cek ketersediaan stok & hitung total
            foreach ($request->items as $item) {
                $product = Product::find($item['product_id']);
                
                if ($product->stok < $item['jumlah']) {
                    DB::rollBack();
                    return $this->sendError("Stok produk {$product->nama} tidak mencukupi.");
                }

                $totalHarga += $product->harga * $item['jumlah'];
            }

            // Buat Order
            $order = Order::create([
                'user_id' => $user->id,
                'total_harga' => $totalHarga,
                'status_pesanan' => 'pending',
                'alamat_pengiriman' => $request->alamat_pengiriman,
            ]);

            // Kurangi stok dan buat OrderItem
            foreach ($request->items as $item) {
                $product = Product::find($item['product_id']);
                
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'jumlah' => $item['jumlah'],
                    'harga_satuan' => $product->harga,
                ]);

                $product->decrement('stok', $item['jumlah']);
            }

            DB::commit();

            return $this->sendResponse($order->load('items.product'), 'Checkout berhasil.', 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return $this->sendError('Terjadi kesalahan pada server.', ['error' => $e->getMessage()], 500);
        }
    }
}
