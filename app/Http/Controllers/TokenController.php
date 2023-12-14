<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TokenController extends Controller
{

    public function createToken(Request $request)
    {

        $request->validate([
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ]);

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials, false)) {
            $user = $request->user();

            $tokenAbilities = [];

            if ($user->isAdmin) {
                $tokenAbilities[] = 'admin';
            }

            $token = $user->createToken('token-name', $tokenAbilities)->plainTextToken;

            return response()->json(['token' => $token]);
        }
    }
}