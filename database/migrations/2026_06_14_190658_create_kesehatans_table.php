<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kesehatans', function (Blueprint $table) {
            $table->id();
            $table->integer('no')->nullable();
            $table->string('nama', 255)->nullable();
            $table->string('pengurusan', 50)->nullable();
            $table->text('alamat')->nullable();
            $table->string('tanggal', 10)->nullable();
            $table->string('penanggung_jawab', 15)->nullable();
            $table->string('file_path')->nullable()->comment('Menyimpan lokasi file di storage server');
            $table->string('dokumen_nama', 255)->nullable();
            $table->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('kesehatans');
    }
};