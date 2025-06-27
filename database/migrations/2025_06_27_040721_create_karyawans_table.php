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
    Schema::create('karyawans', function (Blueprint $table) {
        $table->id();
        $table->string('nama_lengkap');
        $table->string('jabatan');
        $table->string('nik')->nullable();
        $table->string('npwp')->nullable();
        $table->enum('status_karyawan', ['tetap', 'kontrak', 'harian'])->default('kontrak');
        $table->date('tanggal_bergabung');
        $table->decimal('gaji_pokok_default', 15, 2);
        $table->boolean('aktif')->default(true);
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('karyawans');
    }
};
