<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\{
    User,
    Equipament
};

class LoansEquipament extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_requester',
        'id_secretary',
        'id_equipament',
        'justification',
        'authorization_date',
        'withdrawal_date',
        'return_date',
        'status'
    ];


    public function requester(): BelongsTo {
        return $this->belongsTo(User::class, 'id_requester');
    }

    public function secretary(): BelongsTo {
        return $this->belongsTo(User::class, 'id_secretary');
    }

    public function equipament(): BelongsTo {
        return $this->belongsTo(Equipament::class ,'id_equipament');
    }
}
