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
    Schema::create('gajis', function (Blueprint $table) {
        $table->id();
        $table->foreignId('karyawan_id')->constrained('karyawans')->onDelete('cascade');
        $table->string('periode'); // Contoh: "2025-06"

        // Komponen Pendapatan
        $table->decimal('gaji_pokok', 15, 2)->default(0);
        $table->decimal('tunjangan_jabatan', 15, 2)->default(0);
        $table->decimal('tunjangan_transport', 15, 2)->default(0);
        $table->decimal('bonus', 15, 2)->default(0);

        // Komponen Potongan
        $table->decimal('pph21', 15, 2)->default(0);
        $table->decimal('bpjs', 15, 2)->default(0);
        $table->decimal('potongan_lain', 15, 2)->default(0);

        // Total yang Dihitung
        $table->decimal('total_pendapatan', 15, 2);
        $table->decimal('total_potongan', 15, 2);
        $table->decimal('gaji_bersih', 15, 2);

        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gajis');
    }
};