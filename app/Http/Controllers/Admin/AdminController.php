<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    /**
     * Dashboard Admin
     */
    public function dashboard()
    {
        return response()->json([
            'success' => true,
            'message' => 'Selamat datang di Admin Panel!',
            'data' => [
                'user' => auth()->user()
            ]
        ], 200);
    }
}
