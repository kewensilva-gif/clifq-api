<?php

namespace App\Http\Controllers;

use App\DTOs\EquipamentDTO;
use App\Http\Requests\EquipamentStoreRequest;
use App\Http\Requests\EquipamentUpdateRequest;
use App\Models\Equipament;
use App\Services\EquipamentServices;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class EquipamentController extends Controller
{
    use AuthorizesRequests;
    public function __construct(protected EquipamentServices $equipamentServices)
    {}

    public function index() {
        $equipaments = $this->equipamentServices->list();

        return response()->json($equipaments);
    }


    public function store(EquipamentStoreRequest $request) {
        $this->authorize('create', Equipament::class);
        $response = $this->equipamentServices->store($request);

        if($response) {
            return response()->json(["success" => "Equipamento criado com sucesso!"], 201);
        }

        return response()->json(["error" => "Não foi possível criar o equipamento."], 500);
    }

    public function show(Equipament $equipament) {
        if ($equipament) {
            return response()->json(EquipamentDTO::fromModel($equipament), 200);
        }
    
        return response()->json(['error' => 'O equipamento não existe.'], 500);
    }
    

    public function update(EquipamentUpdateRequest $request, Equipament $equipament) {
        $this->authorize('update', Equipament::class);
        $response = $this->equipamentServices->update($request, $equipament);

        if($response) {
            return response()->json(['success' => 'O Equipamento foi atualizado!'], 201);
        }
        return response()->json(['error' => 'Não foi possível atualizar o equipamento.'], 500);
    }

    public function destroy(Equipament $equipament) {
        $this->authorize('delete', Equipament::class);
        $this->equipamentServices->destroy($equipament);

        return response()->json(["success" => "Equipamento removido com sucesso!"], 201);
    }
}
