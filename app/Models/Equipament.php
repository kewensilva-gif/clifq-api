<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\LoansEquipament;

class Equipament extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'manufacturer_number',
        'asset_number',
        'brand',
        'model',
        'image',
        'loaned'
    ];

    public function loansEquipament(): HasMany {
        return $this->hasMany(LoansEquipament::class);
    }
}
