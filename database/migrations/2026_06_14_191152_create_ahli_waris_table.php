<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ahli_waris', function (Blueprint $table) {
            $table->id();
            $table->integer('no')->nullable();
            $table->string('nama', 150)->nullable();
            $table->text('nomor_register')->nullable();
            $table->date('tanggal')->nullable();
            $table->string('file_path')->nullable()->comment('Menyimpan lokasi file di storage server');
            $table->string('dokumen_nama', 255)->nullable();
            $table->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('ahli_waris');
    }
};