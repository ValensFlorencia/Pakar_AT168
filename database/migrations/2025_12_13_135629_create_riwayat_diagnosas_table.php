<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('riwayat_diagnosas', function (Blueprint $table) {
            $table->id();

            // kalau sistem kamu pakai login user
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();

            // ringkasan untuk list riwayat
            $table->string('judul')->nullable(); // contoh: "Hasil Diagnosa - Newcastle Disease"

            // payload hasil diagnosa (gejala terpilih, hasil CF/DS, ranking, dsb)
            $table->json('payload');

            // waktu diagnosa (pakai created_at juga bisa; ini opsional)
            $table->timestamp('diagnosa_at')->useCurrent();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('riwayat_diagnosas');
    }
};
