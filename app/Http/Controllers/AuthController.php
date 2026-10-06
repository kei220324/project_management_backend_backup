<?php

namespace App\Http\Controllers;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\LoginRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function register(RegisterRequest $request)
{
    $validated = $request->validated();

    $user = User::create([
        'name' => $validated['name'],
        'email' => $validated['email'],
        'password' => Hash::make($validated['password']),
    ]);

    return response()->json([
        'user' => $user,
    ], 201);
}
public function login(LoginRequest $request)
{
    $validated = $request->validated();

    $user = User::where('email', $validated['email'])->first();

    if (!$user || !Hash::check($validated['password'], $user->password)) {
        return response()->json([
            'message' => 'メールアドレスまたはパスワードが正しくありません。',
        ], 401);
    }

    Auth::login($user);
    $request->session()->regenerate();

    return response()->json([
        'user' => $user,
    ]);
}


}
