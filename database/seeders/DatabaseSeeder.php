<?php

namespace Database\Seeders;

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
            'name' => 'Manager',
        ]);

        Role::factory()->create([
            'name' => 'Admin',
        ]);

        Role::factory()->create([
            'name' => 'Staff',
        ]);

        $role = Role::where('name', RoleEnum::ADMIN)->first();

        User::create([
            'name' => 'Admin',
            'email' => 'admin@admin.com',
            'password' => Hash::make('123123123'),
            'role_id' => $role->id,
            'position' => 'Admin',
            'born_date' => '2001-01-01',
        ]);
    }
}
