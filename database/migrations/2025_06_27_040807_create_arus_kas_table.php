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
    Schema::create('arus_kas', function (Blueprint $table) {
        $table->id();
        $table->date('tanggal');
        $table->decimal('jumlah', 15, 2);
        $table->enum('tipe', ['masuk', 'keluar']);
        $table->string('deskripsi');
        $table->unsignedBigInteger('referensi_id')->nullable(); // ID dari transaksi/pengadaan
        $table->string('referensi_tipe')->nullable(); // Nama model, misal 'App\Models\Transaction'
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('arus_kas');
    }
};
