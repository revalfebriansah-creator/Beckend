<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AdminController extends Controller
{
    /**
     * Dashboard Admin Statistik.
     */
    public function dashboard(): JsonResponse
    {
        $totalUsers = User::where('role', 'customer')->count();
        $totalProducts = Product::count();
        $totalOrders = Order::count();
        $totalRevenue = Order::where('status_pesanan', 'completed')->sum('total_harga');

        return $this->sendResponse([
            'stats' => [
                'total_customers' => $totalUsers,
                'total_products' => $totalProducts,
                'total_orders' => $totalOrders,
                'total_revenue' => $totalRevenue,
            ],
            'user' => auth()->user()
        ], 'Statistik Dashboard Admin berhasil diambil.');
    }

    /**
     * Update status pesanan (oleh admin).
     */
    public function updateOrderStatus(Request $request, $id): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'status_pesanan' => 'required|in:pending,processing,completed,cancelled'
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validasi gagal.', $validator->errors(), 422);
        }

        $order = Order::find($id);

        if (!$order) {
            return $this->sendError('Pesanan tidak ditemukan.');
        }

        $order->update([
            'status_pesanan' => $request->status_pesanan
        ]);

        return $this->sendResponse($order, 'Status pesanan berhasil diperbarui.');
    }
}
