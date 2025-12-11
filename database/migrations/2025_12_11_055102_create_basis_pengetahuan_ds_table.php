<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('basis_pengetahuan_ds', function (Blueprint $table) {
            $table->id();

            // Relasi ke tabel penyakit
            $table->foreignId('penyakit_id')
                  ->constrained('penyakits')
                  ->onDelete('cascade');

            // Relasi ke tabel gejala
            $table->foreignId('gejala_id')
                  ->constrained('gejala')
                  ->onDelete('cascade');

            // Nilai DS (misalnya 0.1 – 1.0)
            $table->decimal('ds_value', 4, 2);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('basis_pengetahuan_ds');
    }
};
