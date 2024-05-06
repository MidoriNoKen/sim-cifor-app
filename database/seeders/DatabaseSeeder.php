<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        Role::factory()->create([
            'name' => 'Manager',
        ]);

        Role::factory()->create([
            'name' => 'Admin',
        ]);

        Role::factory()->create([
            'name' => 'User',
        ]);

        User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@admin.com',
            'password' => bcrypt('313131'),
            'role_id' => 1,
            'supervisor_id' => null,
            'manager_id' => null,
            'position' => 'Admin',
            'born_date' => '2001-01-01',
        ]);
    }
}
