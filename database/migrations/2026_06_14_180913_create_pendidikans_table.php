<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pendidikans', function (Blueprint $table) {
            $table->id(); 
            $table->integer('no')->nullable();
            $table->string('nama', 50)->nullable();
            $table->string('pengurusan', 100)->nullable();
            $table->string('alamat', 150)->nullable();
            $table->string('tanggal')->nullable();
            $table->string('penanggung_jawab', 10)->nullable();
            $table->string('file_path')->nullable()->comment('Menyimpan lokasi file di storage server');
            $table->string('dokumen_nama', 255)->nullable();
            $table->timestamps(); 
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pendidikans');
    }
};