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
    Schema::create('barangs', function (Blueprint $table) {
        $table->id();
        $table->string('kode_barang')->unique()->nullable();
        $table->string('nama');
        $table->foreignId('kategori_id')->constrained('kategoris');
        $table->string('unit');
        $table->integer('stok')->default(0);
        $table->decimal('harga_jual', 15, 2)->default(0);
        $table->string('jenis_kain')->nullable();
        $table->string('warna')->nullable();
        $table->string('ukuran')->nullable();
        $table->string('kualitas')->nullable();
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('barangs');
    }
};
