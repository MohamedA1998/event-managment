<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\AuthRequest;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller implements HasMiddleware
{
    public static function Middleware(): array
    {
        return [
            new Middleware('guest', ['store']),
            new Middleware('auth:api', ['destroy']),
        ];
    }

    public function store(AuthRequest $request)
    {
        if(!$token = auth()->guard('api')->attempt($request->validated())) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        return response(['token' => $token]);
    }

    public function destroy(string $id)
    {
        auth()->guard('api')->logout();

        return response()->noContent();
    }
}
