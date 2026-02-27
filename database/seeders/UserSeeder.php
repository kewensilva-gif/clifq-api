<?php

namespace Database\Seeders;

use App\Enums\RoleEnum;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Kéwen Silva',
            'role_id' => RoleEnum::ADMIN,
            'nickname' => 'kewensilva',
            'email' => 'silvakewen52@gmail.com',
            'matriculation' => '202100007563',
            'phone' => '(75) 99290-1578',
            'activated' => true,
        ]);
        User::factory()->create([
            'name' => 'Kéwen Silva',
            'role_id' => RoleEnum::SECRETARY,
            'nickname' => 'devSecretary',
            'email' => 'kewenlinkedin@gmail.com',
            'matriculation' => '202900007563',
            'phone' => '(75) 99290-1578',
            'activated' => true,
        ]);
        User::factory()->create([
            'name' => 'Kéwen Silva',
            'role_id' => RoleEnum::TECH,
            'nickname' => 'devTech',
            'email' => 'kewensilva58@gmail.com',
            'matriculation' => '202800007563',
            'phone' => '(75) 99290-1578',
            'activated' => true,
        ]);
    }
}
