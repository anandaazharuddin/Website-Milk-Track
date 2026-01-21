<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\PosPenyetoran;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class AdminPosSeeder extends Seeder
{
    public function run(): void
    {
        // Disable foreign key checks temporarily
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // Buat/Update Pos Boro
        $posBoro = PosPenyetoran::updateOrCreate(
            ['kode_pos' => 'POS001'],
            [
                'nama_pos' => 'Pos Boro',
                'lokasi' => 'Boro, Karangploso',
                'keterangan' => 'Pos penampungan susu wilayah Boro',
                'is_active' => true,
            ]
        );

        // Buat/Update Pos Tawangargo
        $posTawangargo = PosPenyetoran::updateOrCreate(
            ['kode_pos' => 'POS002'],
            [
                'nama_pos' => 'Pos Tawangargo',
                'lokasi' => 'Tawangargo, Karangploso',
                'keterangan' => 'Pos penampungan susu wilayah Tawangargo',
                'is_active' => true,
            ]
        );

        // Re-enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Super Admin (bisa akses semua pos)
        $superAdmin = User::updateOrCreate(
            ['username' => 'superadmin'],
            [
                'name' => 'Super Administrator',
                'email' => 'superadmin@milktrack.com',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'pos_id' => null, // NULL = akses semua pos
            ]
        );

        // Admin Pos Boro
        $adminBoro = User::updateOrCreate(
            ['username' => 'admin_boro'],
            [
                'name' => 'Admin Pos Boro',
                'email' => 'admin.boro@milktrack.com',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'pos_id' => $posBoro->id,
            ]
        );

        // Admin Pos Tawangargo
        $adminTawangargo = User::updateOrCreate(
            ['username' => 'admin_tawangargo'],
            [
                'name' => 'Admin Pos Tawangargo',
                'email' => 'admin.tawangargo@milktrack.com',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'pos_id' => $posTawangargo->id,
            ]
        );

        // Output hasil
        $this->command->info('✅ Seeder berhasil dijalankan!');
        $this->command->info('');
        $this->command->table(
            ['Username', 'Password', 'Role', 'Pos', 'Email'],
            [
                ['superadmin', 'password', 'admin', 'Semua Pos', 'superadmin@milktrack.com'],
                ['admin_boro', 'password', 'admin', $posBoro->nama_pos, 'admin.boro@milktrack.com'],
                ['admin_tawangargo', 'password', 'admin', $posTawangargo->nama_pos, 'admin.tawangargo@milktrack.com'],
            ]
        );
        
        $this->command->info('');
        $this->command->info('📍 Pos yang tersedia:');
        $this->command->info("   - {$posBoro->kode_pos}: {$posBoro->nama_pos} ({$posBoro->lokasi})");
        $this->command->info("   - {$posTawangargo->kode_pos}: {$posTawangargo->nama_pos} ({$posTawangargo->lokasi})");
    }
}