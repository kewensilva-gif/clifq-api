<?php

namespace App\Services;

use App\DTOs\LoanEquipamentDTO;
use App\Enums\EquipamentEnum;
use App\Enums\LoanStatusEnum;
use App\Http\Requests\AuthorizeLoanRequest;
use App\Http\Requests\LoansEquipamentStoreRequest;
use App\Http\Requests\LoansEquipamentUpdateRequest;
use App\Models\Equipament;
use App\Models\LoansEquipament;
use Illuminate\Http\JsonResponse;
use DB;
use Log;
use Request;
use Throwable;

class LoansEquipamentServices
{
    public function list()
    {
        $request = request();
        $paginated = LoansEquipament::with(['requester', 'secretary', 'equipament'])
        ->when($request->id_requester, function ($query, $requesterId) {
            return $query->where('id_requester', $requesterId);
        })
        ->when($request->id_secretary, function ($query, $secretaryId) {
            return $query->where('id_secretary', $secretaryId);
        })
        ->when($request->id_equipament, function ($query, $equipamentId) {
            return $query->where('id_equipament', $equipamentId);
        })
        ->paginate(10);

        $data = $paginated->getCollection()
            ->map(fn($loan) => LoanEquipamentDTO::fromModel($loan));
        
        $paginated->setCollection($data);

        return response()->json([
            'data' => $paginated,
            'status_list' => LoanStatusEnum::options(),
        ]);
    }
    public function listFromUser()
    {
        $paginated = LoansEquipament::with(['requester', 'secretary', 'equipament'])
        ->when(auth()->user()->id, function ($query, $requesterId) {
            return $query->where('id_requester', $requesterId);
        })
        ->paginate(10);

        $data = $paginated->getCollection()
            ->map(fn($loan) => LoanEquipamentDTO::fromModel($loan));
        
        $paginated->setCollection($data);

        return response()->json([
            'data' => $paginated,
            'status_list' => LoanStatusEnum::options(),
        ]);
    }

    public function store(LoansEquipamentStoreRequest $request): bool|LoansEquipament|Model
    {
        try {
            $loans = $request->validated();
            $equipament = Equipament::findOrFail($loans['id_equipament']);

            if($equipament && !$equipament->loaned) {
                $response = LoansEquipament::create($loans);
    
                return $response;
            }

            Log::error('Equipamento está indisponível', ['exception' => 'Equipment unavailable']);
            return false;
        } catch (Throwable $e) {
            Log::error('Erro ao criar empréstimo', ['exception' => $e]);

            return false;
        }
    }

    public function update(LoansEquipamentUpdateRequest $request, LoansEquipament $loans): bool {
        return $loans->update($request->validated());
    }
    
    public function authorization(LoansEquipament $loan, AuthorizeLoanRequest $request): JsonResponse
    {
        try {
            $loan->loadMissing('equipament');
            $payload = $request->validated();

            if($loan->status !== LoanStatusEnum::PENDENTE->value || 
            ($payload['status'] === LoanStatusEnum::AUTORIZADO->value &&
            $loan->equipament->loaned)) 
            {
                return response()->json(["error" => "Equipamento não está disponível para empréstimo"], 500);
            }

            return DB::transaction(function () use ($loan, $payload) {
                $payload['authorization_date'] = now();

                $loan->equipament->update(
                    [
                        'loaned' => $payload['status'] == LoanStatusEnum::AUTORIZADO->value
                        ? EquipamentEnum::EMPRESTADO
                        : EquipamentEnum::DISPONIVEL
                    ]
                );

                $loan->update($payload);

                $statusLabel = LoanStatusEnum::fromValue($payload['status'])->label();
                return response()->json([
                    'success' => "Empréstimo {$statusLabel} com sucesso!"
                ], 201);
            });
        } catch (Throwable $e) {
            Log::error('Erro ao autorizar empréstimo', ['exception' => $e]);
            
            return response()->json(['error' => 'Não foi possível realizar a ação solicitada.'], 500);
        }
    }

    public function returnEquipament($loan) {
        try {
            return DB::transaction( function () use($loan) {
                $loan->loadMissing('equipament');
                $loan->equipament->update(["loaned" => EquipamentEnum::DISPONIVEL]);
                
                return $loan->update(["return_date" => now()]);
            });
        } catch(Throwable $e) {
            Log::error('Erro ao devolver o equipamento', ['exception' => $e]);
            return false;
        }
    }

    public function destroy(LoansEquipament $loans): bool|null {
        $response = $loans->delete();

        return $response;
    }
}