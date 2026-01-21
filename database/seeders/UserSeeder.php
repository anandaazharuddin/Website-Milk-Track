<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Matikan constraint saat truncate
        Schema::disableForeignKeyConstraints();
        User::truncate();
        Schema::enableForeignKeyConstraints();

        // Admin
        User::create([
            'name' => 'Admin',
            'username' => 'admin',
            'email' => 'admin@milktrack.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // Peternak
        User::create([
            'name' => 'Peternak 1',
            'username' => 'peternak1',
            'email' => 'peternak1@milktrack.com',
            'password' => Hash::make('password'),
            'role' => 'peternak',
        ]);

        User::create([
            'name' => 'Peternak 2',
            'username' => 'peternak2',
            'email' => 'peternak2@milktrack.com',
            'password' => Hash::make('password'),
            'role' => 'peternak',
        ]);

        $this->command->info('✅ Users seeded successfully!');
        $this->command->info('Admin: username=admin, password=password');
        $this->command->info('Peternak: username=peternak1, password=password');
    }
}
