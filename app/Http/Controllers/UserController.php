<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;

class UserController extends Controller
{
    public function __construct(protected UserService $userService) {}
    public function index(): JsonResponse {
        $users = $this->userService->list();
        return response()->json($users);
    }

    public function show(User $user): JsonResponse {
        if($user) {
            return response()->json($user, 200);
        }

        return response()->json(['error' => 'Usuário não encontrado.'], 500);
    }
}
