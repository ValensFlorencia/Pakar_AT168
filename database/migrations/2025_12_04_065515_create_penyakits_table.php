<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
        public function up()
        {
            Schema::create('penyakits', function (Blueprint $table) {
                $table->id();
                $table->string('kode_penyakit')->unique();
                $table->string('nama_penyakit');
                $table->text('deskripsi')->nullable();
                $table->text('solusi')->nullable();
                $table->timestamps();
            });
            Schema::table('penyakits', function (Blueprint $table) {
                $table->text('solusi')->nullable()->after('deskripsi');
    });
        }


    public function down()
    {
        Schema::dropIfExists('penyakits');
        Schema::table('penyakits', function (Blueprint $table) {
            $table->dropColumn('solusi');
    });
    }

};
