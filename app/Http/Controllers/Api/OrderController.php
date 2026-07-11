<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Tampilkan riwayat pesanan user atau semua pesanan untuk admin.
     */
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();

        if ($user->isAdmin()) {
            $orders = Order::with(['user', 'items.product'])->latest()->get();
        } else {
            $orders = Order::where('user_id', $user->id)->with('items.product')->latest()->get();
        }

        return $this->sendResponse($orders, 'Data pesanan berhasil diambil.');
    }

    /**
     * Tampilkan detail pesanan.
     */
    public function show(Request $request, $id): JsonResponse
    {
        $user = $request->user();
        $order = Order::with(['user', 'items.product'])->find($id);

        if (!$order) {
            return $this->sendError('Pesanan tidak ditemukan.');
        }

        if (!$user->isAdmin() && $order->user_id !== $user->id) {
            return $this->sendError('Anda tidak memiliki akses ke pesanan ini.', [], 403);
        }

        return $this->sendResponse($order, 'Detail pesanan berhasil diambil.');
    }
}
