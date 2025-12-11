<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('basis_pengetahuan_cf', function (Blueprint $table) {
            $table->id();

            // cukup simpan id-nya saja, tanpa foreign key constraint
            $table->unsignedBigInteger('penyakit_id');
            $table->unsignedBigInteger('gejala_id');

            $table->decimal('cf_value', 3, 2); // 0.00 – 1.00
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('basis_pengetahuan_cf');
    }
};
