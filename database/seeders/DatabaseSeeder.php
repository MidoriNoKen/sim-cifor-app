<?php

namespace Database\Seeders;

use App\Enums\PositionEnum;
use App\Enums\RoleEnum;
use App\Models\Role;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Role::factory()->create([
            'name' => RoleEnum::ADMIN,
        ]);

        Role::factory()->create([
            'name' => RoleEnum::EMPLOYEE,
        ]);

        Role::factory()->create([
            'name' => RoleEnum::MANAGER,
        ]);

        Role::factory()->create([
            'name' => RoleEnum::DIRECTOR,
        ]);

        $role = Role::where('name', RoleEnum::ADMIN)->first();

        User::create([
            'name' => 'Admin',
            'email' => 'admin@admin.com',
            'password' => Hash::make('123123123'),
            'role_id' => $role->id,
            'position' => PositionEnum::ADMIN,
            'born_date' => '2001-01-01',
        ]);
    }
}
