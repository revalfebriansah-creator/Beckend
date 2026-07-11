<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    /**
     * Dashboard Admin.
     *
     * @return JsonResponse
     */
    public function dashboard(): JsonResponse
    {
        return $this->sendResponse([
            'user' => auth()->user()
        ], 'Selamat datang di Admin Panel!');
    }
}
