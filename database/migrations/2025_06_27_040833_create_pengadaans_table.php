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
        Schema::create('pengadaans', function (Blueprint $table) {
            $table->id();
            $table->string('no_invoice');
            $table->foreignId('supplier_id')->constrained('suppliers')->onDelete('cascade');
            $table->foreignId('barang_id')->constrained('barangs')->onDelete('cascade');
            $table->date('tanggal_pembelian'); // Menggunakan nama yang konsisten
            $table->integer('jumlah_masuk'); // Menggunakan nama yang konsisten
            $table->decimal('harga_beli', 15, 2); // Menggunakan nama yang konsisten
            $table->decimal('total_harga', 15, 2);
            $table->text('keterangan')->nullable();
            $table->string('bukti')->nullable(); // Kolom bukti langsung ditambahkan di sini
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengadaans');
    }
};
