<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pos_penyetoran', function (Blueprint $table) {
            $table->id();
            $table->string('kode_pos')->unique();
            $table->string('nama_pos');
            $table->string('lokasi')->nullable();
            $table->text('keterangan')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('peternak', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->string('kode_peternak')->unique();
            $table->string('nama_peternak');
            $table->foreignId('pos_id')->constrained('pos_penyetoran')->onDelete('cascade');
            $table->string('alamat')->nullable();
            $table->string('no_hp')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('penyetoran_harian', function (Blueprint $table) {
            $table->id();
            $table->foreignId('peternak_id')->constrained('peternak')->onDelete('cascade');
            $table->foreignId('pos_id')->constrained('pos_penyetoran')->onDelete('cascade');
            $table->date('tanggal');
            
            $table->decimal('volume_pagi', 10, 2)->nullable()->default(0);
            $table->integer('bj_pagi')->nullable(); // ← UBAH: simpan sebagai integer (1234)
            
            $table->decimal('volume_sore', 10, 2)->nullable()->default(0);
            $table->integer('bj_sore')->nullable(); // ← UBAH: simpan sebagai integer (1234)
            
            $table->text('catatan')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();

            $table->unique(['peternak_id', 'tanggal']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('penyetoran_harian');
        Schema::dropIfExists('peternak');
        Schema::dropIfExists('pos_penyetoran');
    }
};