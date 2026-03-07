<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Peternak;
use App\Models\PosPenyetoran;
use Illuminate\Support\Facades\Hash;

class PeternakUserSeeder extends Seeder
{
    /**
     * Seed user accounts untuk peternak
     * 
     * Jalankan: php artisan db:seed --class=PeternakUserSeeder
     */
    public function run(): void
    {
        // Contoh 1: Membuat user peternak baru dan menghubungkan dengan data peternak existing
        $this->createPeternakWithUser('P001', 'Budi Santoso', 'budi', 'password123', 1);
        $this->createPeternakWithUser('P002', 'Siti Aminah', 'siti', 'password123', 1);
        
        // Contoh 2: Menghubungkan user existing dengan data peternak
        // $this->linkExistingUserToPeternak('username_peternak', 'P003');
        
        $this->command->info('✅ Seeder Peternak User selesai!');
        $this->command->info('   Username: budi / siti, Password: password123');
    }

    /**
     * Buat user baru untuk peternak (atau update existing peternak dengan user_id)
     */
    private function createPeternakWithUser($kodePeternak, $namaPeternak, $username, $password, $posId)
    {
        // Cek apakah peternak sudah ada
        $peternak = Peternak::where('kode_peternak', $kodePeternak)->first();
        
        if (!$peternak) {
            // Buat peternak baru jika belum ada
            $peternak = Peternak::create([
                'kode_peternak' => $kodePeternak,
                'nama_peternak' => $namaPeternak,
                'pos_id' => $posId,
                'is_active' => true,
            ]);
            $this->command->info("   ➕ Peternak baru: {$kodePeternak} - {$namaPeternak}");
        } else {
            $this->command->info("   ✓ Peternak sudah ada: {$kodePeternak}");
        }

        // Cek apakah user sudah ada
        $user = User::where('username', $username)->first();
        
        if (!$user) {
            // Buat user baru
            $user = User::create([
                'name' => $namaPeternak,
                'username' => $username,
                'email' => $username . '@milktrack.test',
                'password' => Hash::make($password),
                'role' => 'peternak',
                'pos_id' => $posId,
            ]);
            $this->command->info("   ➕ User baru: {$username}");
        } else {
            $this->command->info("   ✓ User sudah ada: {$username}");
        }

        // Hubungkan peternak dengan user
        $peternak->update(['user_id' => $user->id]);
        $this->command->info("   🔗 Linked: {$kodePeternak} <-> {$username}\n");
    }

    /**
     * Hubungkan user existing dengan peternak existing
     */
    private function linkExistingUserToPeternak($username, $kodePeternak)
    {
        $user = User::where('username', $username)->first();
        $peternak = Peternak::where('kode_peternak', $kodePeternak)->first();

        if (!$user) {
            $this->command->error("   ❌ User tidak ditemukan: {$username}");
            return;
        }

        if (!$peternak) {
            $this->command->error("   ❌ Peternak tidak ditemukan: {$kodePeternak}");
            return;
        }

        $peternak->update(['user_id' => $user->id]);
        $this->command->info("   🔗 Linked: {$kodePeternak} <-> {$username}");
    }
}
