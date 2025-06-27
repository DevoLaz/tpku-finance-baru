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
        $table->foreignId('barang_id')->constrained();
        $table->foreignId('supplier_id')->constrained();
        $table->date('tanggal_pembelian');
        $table->string('no_invoice')->nullable();
        $table->integer('jumlah_masuk');
        $table->decimal('harga_beli', 15, 2);
        $table->decimal('total_harga', 15, 2);
        $table->text('keterangan')->nullable();
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