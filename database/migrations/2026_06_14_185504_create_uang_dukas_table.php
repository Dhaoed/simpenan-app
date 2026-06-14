<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('uang_dukas', function (Blueprint $table) {
            $table->id();
            $table->integer('no')->nullable();
            $table->string('nama', 100)->nullable();
            $table->date('tanggal_wafat')->nullable();
            $table->string('ahli_waris', 100)->nullable();
            $table->date('tanggal_terima')->nullable();
            $table->string('alamat', 250)->nullable();
            $table->string('kelurahan', 100)->nullable();
            $table->string('tanggal_kirim', 50)->nullable();
            $table->string('file_path')->nullable()->comment('Menyimpan lokasi file di storage server');
            $table->string('dokumen_nama', 255)->nullable();
            $table->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('uang_dukas');
    }
};