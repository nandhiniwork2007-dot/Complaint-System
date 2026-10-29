<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class RegisteredUserController extends Controller
{
    public function store(RegisterRequest $request): JsonResponse
    {
        $user = User::query()->create($request->validated());
        $user->markEmailAsVerified();

        Auth::login($user);
        $request->session()->regenerate();

        return response()->json([
            'message' => 'Registration completed successfully.',
            'user' => new UserResource($user),
        ], 201);
    }
}
