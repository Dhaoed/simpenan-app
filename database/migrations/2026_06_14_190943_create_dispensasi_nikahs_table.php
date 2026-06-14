<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dispensasis', function (Blueprint $table) {
            $table->id();
            $table->integer('no')->nullable();
            $table->string('nama_suami', 150)->nullable();
            $table->string('nama_istri', 150);
            $table->text('nomor_surat')->nullable();
            $table->text('pengantar_KUA')->nullable();
            $table->string('tanggal', 50)->nullable();
            $table->string('file_path')->nullable()->comment('Menyimpan lokasi file di storage server');
            $table->string('dokumen_nama', 255)->nullable();
            $table->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('dispensasis');
    }
};