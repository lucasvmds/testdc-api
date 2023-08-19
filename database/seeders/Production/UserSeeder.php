<?php

namespace Database\Seeders\Production;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::query()->create([
            'name' => 'Administrador',
            'email' => 'dev@developer',
            'role' => UserRole::ADMIN,
            'password' => Hash::make('Ab123456789@'),
        ]);
    }
}
