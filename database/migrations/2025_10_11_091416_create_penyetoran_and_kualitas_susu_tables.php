<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migration
     */
    public function up(): void
    {
        // Tabel utama: penyetoran susu
        Schema::create('penyetoran', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal');
            $table->float('berat_jenis')->nullable(); // nullable biar bisa diinput sebagian
            $table->float('volume_liter')->nullable();
            $table->decimal('nilai_uang', 12, 2)->nullable();
            $table->string('status_kualitas')->default('baik'); // baik / di bawah standar
            $table->timestamps();
        });

        // Tabel kualitas susu (detail)
        Schema::create('kualitas_susu', function (Blueprint $table) {
            $table->id();
            $table->foreignId('penyetoran_id')
                ->constrained('penyetoran')
                ->onDelete('cascade');
            $table->float('berat_jenis');
            $table->string('bau');
            $table->string('warna');
            $table->float('suhu');
            $table->string('status')->default('baik');
            $table->timestamps();
        });
    }

    /**
     * Rollback migration
     */
    public function down(): void
    {
        Schema::dropIfExists('kualitas_susu');
        Schema::dropIfExists('penyetoran');
    }
};
