<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class TestController extends Controller
{
    public function showUsers()
    {
        $users = User::with('roles')->get()->map(function($user) {
            return [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'roles' => $user->roles->pluck('name')
            ];
        })->values()->toArray();

        return view('test.users', compact('users'));
    }
}
