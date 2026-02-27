<?php
namespace App\DTOs;
use App\Enums\LoanStatusEnum;
use App\Models\LoansEquipament;
use Carbon\Carbon;

class LoanEquipamentDTO
{
  public static function fromModel(LoansEquipament $loansEquipament): array
  {
    return [
      'id' => $loansEquipament->id,
      // 'id_requester' => $loansEquipament->id_requester,
      // 'id_secretary' => $loansEquipament->id_secretary,
      'id_equipament' => $loansEquipament->id_equipament,
      'status_number' => $loansEquipament->status,
      'justification' => $loansEquipament->justification,
      'requester_name' => $loansEquipament->requester?->name,
      'secretary_name' => $loansEquipament->secretary?->name,
      'equipament_name' => $loansEquipament->equipament?->name,
      'status' => LoanStatusEnum::fromValue(value: $loansEquipament->status)->label(),
      'authorization_date' => $loansEquipament->authorization_date ? Carbon::parse($loansEquipament->authorization_date)->format('d/m/Y') : null,
      'withdrawal_date' => $loansEquipament->withdrawal_date ? Carbon::parse($loansEquipament->withdrawal_date)->format('d/m/Y') : null,
      'return_date' => $loansEquipament->return_date ? Carbon::parse($loansEquipament->return_date)->format('d/m/Y') : null,
      'created_at' => optional($loansEquipament->created_at)->format('d/m/Y'),
      'image_equipament' => $loansEquipament->equipament?->image
    ];
  }
}