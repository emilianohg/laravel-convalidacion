<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;

class LoginController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {

        $request->validate([
            'email'     => 'required|email',
            'password'  => 'required',
            'device'    => 'required'
        ]);

        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'message' => 'Unauthorized'
            ], 401);
        }

        return response()->json([
            'token'     => $request->user()->createToken($request->device)->plainTextToken,
            'user'      => auth()->user(),
            'message'   => 'Success'
        ]);
    }
}
