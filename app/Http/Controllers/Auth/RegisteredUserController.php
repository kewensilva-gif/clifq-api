<?php

namespace App\Http\Controllers\Auth;

use App\Enums\RoleEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\UserStoreRequest;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class RegisteredUserController extends Controller
{
    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(UserStoreRequest $request): JsonResponse
    {
       
        $user = User::create([
            'name' => $request->name,
            'nickname' => $request->nickname,
            'role_id' => RoleEnum::TECH,
            'nif' => $request->nif,
            'email' => $request->email,
            'password' => Hash::make($request->string('password')),
            'matriculation' => $request->matriculation,
            'phone' => $request->phone,
        ]);


        event(new Registered($user));

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json(['Token' => $token]);
    }
}
