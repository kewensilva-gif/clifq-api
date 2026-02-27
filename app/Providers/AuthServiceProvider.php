<?php

namespace App\Providers;

use App\Models\LoansEquipament;
use App\Policies\LoanEquipamentPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        LoansEquipament::class => LoanEquipamentPolicy::class,
    ];

    public function boot(): void
    {
        $this->registerPolicies();
    }
}

