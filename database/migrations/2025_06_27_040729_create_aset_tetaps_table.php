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
    Schema::create('aset_tetaps', function (Blueprint $table) {
        $table->id();
        $table->string('nama_aset');
        $table->string('kategori'); // Kita pakai lagi untuk membedakan 'Gedung', 'Kendaraan', dll.
        $table->date('tanggal_perolehan');
        $table->decimal('harga_perolehan', 15, 2);
        $table->integer('masa_manfaat')->comment('Dalam tahun');
        $table->decimal('nilai_residu', 15, 2)->default(0);
        $table->text('deskripsi')->nullable();
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('aset_tetaps');
    }
};
