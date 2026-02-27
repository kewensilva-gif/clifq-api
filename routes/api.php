<?php

use App\Http\Controllers\EquipamentController;
use App\Http\Controllers\LoansEquipamentController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Laravel\Sanctum\Sanctum;

// Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
//     return $request->user();
// });
Route::get('user/me', function(){
    return auth()->user();
})->middleware('auth:sanctum');

Route::prefix('users')->middleware('auth:sanctum')->group(function() {
    Route::get('', [UserController::class, 'index']);
    Route::get('{user}', [UserController::class, 'show']);
});
/* Route::middleware('auth:sanctum')->apiResource('equipaments', EquipamentController::class); */
Route::apiResource('equipaments', EquipamentController::class)->middleware('auth:sanctum');

Route::get('loans/my_loans', action: [LoansEquipamentController::class, 'indexFromUser'])->middleware('auth:sanctum');
Route::apiResource('loans', LoansEquipamentController::class)->middleware('auth:sanctum');
Route::prefix('loans/{loan}')->middleware('auth:sanctum')->group(function () {
    Route::post('authorization', [LoansEquipamentController::class, 'authorization']);
    Route::post('withdrawal', [LoansEquipamentController::class, 'withdrawal']);
    Route::post('return', action: [LoansEquipamentController::class, 'returnEquipament']);
});



require __DIR__.'/auth.php';