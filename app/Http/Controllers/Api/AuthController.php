<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function checkPassword(Request $request)
    {
        $request->validate([
            'user_id' => 'required|integer|exists:users,id',
            'password' => 'required|string',
        ]);

        $user = User::find($request->user_id);

        if ($user && Hash::check($request->password, $user->password)) {
            return response()->json(['valid' => true], 200);
        } else {
            return response()->json(['valid' => false], 200);
        }
    }
}
