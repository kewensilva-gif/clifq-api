<?php

namespace App\Http\Controllers;

use App\DTOs\LoanEquipamentDTO;
use App\Enums\LoanStatusEnum;
use App\Http\Requests\AuthorizeLoanRequest;
use App\Http\Requests\LoansEquipamentStoreRequest;
use App\Http\Requests\LoansEquipamentUpdateRequest;
use App\Models\LoansEquipament;
use App\Services\LoansEquipamentServices;
use Illuminate\Http\JsonResponse;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class LoansEquipamentController extends Controller
{
    use AuthorizesRequests;
    public function __construct(protected LoansEquipamentServices $loansEquipamentServices)
    {}

    public function index() {
        $this->authorize('viewAny', LoansEquipament::class);

        $loans = $this->loansEquipamentServices->list();

        return response()->json($loans, 200);
    }
    
    public function indexFromUser() {
        $loans = $this->loansEquipamentServices->listFromUser();

        return response()->json($loans, 200);
    }


    public function store(LoansEquipamentStoreRequest $request): JsonResponse {
        $response = $this->loansEquipamentServices->store($request);

        if($response) {
            return response()->json($response, 201);
        }

        return response()->json(
        [
            'error' => 'Erro ao registrar empréstimo. Tente novamente mais tarde.'
        ], 500);
    }

    public function show(LoansEquipament $loan): JsonResponse {
        if($loan) {
            return response()->json([
                'data' => LoanEquipamentDTO::fromModel($loan),
                'status_list' => LoanStatusEnum::options(),
            ], 200);
        }

        return response()->json(['error' => 'O equipamento não existe.'], 500);
    }    

    public function update(LoansEquipamentUpdateRequest $request, LoansEquipament $loansEquipament) {
        $this->authorize('update', LoansEquipament::class);
        $response = $this->loansEquipamentServices->update($request, $loansEquipament);

        if($response) {
            return response()->json(['success' => 'O Equipamento foi atualizado!'], 201);
        }
        return response()->json(['error' => 'Não foi possível atualizar o equipamento.'], 500);
    }

    public function destroy(LoansEquipament $loan): JsonResponse {
        $this->authorize('delete', LoansEquipament::class);

        $this->loansEquipamentServices->destroy($loan);

        return response()->json(["success" => "Equipamento removido com sucesso!"], 201);
    }

    public function authorization(AuthorizeLoanRequest $request, LoansEquipament $loan): JsonResponse
    {
        $this->authorize('update', LoansEquipament::class);

        $response = $this->loansEquipamentServices->authorization($loan, $request);
        return $response;
    }

    public function withdrawal(LoansEquipament $loan): JsonResponse
    {
        $this->authorize('update', LoansEquipament::class);

        $loan->withdrawal_date = now();
        $loan->save();

        return response()->json(['success' => 'Equipamento retirado com sucesso!'], 201);
    }

    public function returnEquipament(LoansEquipament $loan): JsonResponse
    {
        $this->authorize('update', LoansEquipament::class);

        $success = $this->loansEquipamentServices->returnEquipament($loan);

        if ($success) {
            return response()->json(['success' => 'Equipamento devolvido com sucesso'], 201);
        }

        return response()->json(['error' => 'Não foi possível devolver o equipamento!'], 500);
    }
}
