<?php
namespace App\DTOs;
use App\Enums\EquipamentEnum;
use App\Models\Equipament;

class EquipamentDTO
{
    public static function fromModel(Equipament $equipament): array
    {
      return [
        'id' => $equipament->id,
        'name' => $equipament->name,
        'description' => $equipament->description,
        'manufacturer_number' => $equipament->manufacturer_number,
        'asset_number' => $equipament->asset_number,
        'brand' => $equipament->brand,
        'model' => $equipament->model,
        'image' => $equipament->image,
        'loaned' => $equipament->loaned,
        'status_list' => EquipamentEnum::options(),
      ];
    }
}
