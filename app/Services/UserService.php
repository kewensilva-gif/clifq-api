<?php

namespace App\Services;

use App\Models\User;

class UserService {
  public function list() {
    $request = request();
    $paginated = User::query()
    ->when($request->id, function($query, $id) {
      return $query->where('id', $id);
    })
    ->when($request->search, function ($query, $search) {
      return $query->where(function ($q) use($search) {
        $q->where('name', 'like', "%{$search}%")
        ->orWhere('nickname', 'like', "%{$search}%")
        ->orWhere('email', 'like', "%{$search}%");
      });
    })
    ->paginate(10);

    return response()->json($paginated);
  }
}