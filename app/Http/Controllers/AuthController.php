<?php

namespace App\Http\Controllers;

use App\Enums\UserRoleEnum;
use App\Http\Requests\UserLoginRequest;
use App\Http\Requests\UserRegistrationRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    /**
     * user registration
     * @param UserRegistrationRequest $request
     * @return JsonResponse
     */
    public function register(UserRegistrationRequest $request)
    {
        $validated = $request->validated();
        $validated['password'] = Hash::make($validated['password']);
        $user = User::create($validated);
        return response()->json(['message' => __('messages.user_created')], 201);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function login(UserLoginRequest $request)
    {
        //this for mobile/postman
        $validated = $request->validated();
        $user = User::where('email', $validated['email'])->first();
        if (!$user || !Hash::check($validated['password'], $user->password)) {
            return response()->json(['message' => __('messages.invalid_credentials')], 401);
        }

        $token = $user->createToken('api_token')->plainTextToken;
        return response()->json(['message' => __('messages.logged_in'), 'token' => $token], 200);
    }

    // 🔹 Logout
    public function logout(Request $request)
    {
        Auth::logout();
        return response()->json([
            'message' => __('messages.logged_out')
        ], 401);
    }
}
