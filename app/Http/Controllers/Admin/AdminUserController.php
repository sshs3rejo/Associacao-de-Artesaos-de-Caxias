<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;

class AdminUserController extends Controller
{
    public function index()
    {
        $usuarios = User::with('artisanProfile')
            ->where('id', '!=', auth()->id())
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.usuarios', compact('usuarios'));
    }
}
