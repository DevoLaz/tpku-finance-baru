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
        Schema::create('barang_supplier', function (Blueprint $table) {
    $table->id();
    $table->foreignId('supplier_id')
          ->constrained()
          ->cascadeOnDelete();
    $table->foreignId('barang_id')
          ->constrained('barangs')
          ->cascadeOnDelete();
    $table->decimal('harga_beli', 15, 2)->default(0);
    $table->timestamps();
    $table->unique(['supplier_id','barang_id']);
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('barang_supplier');
    }
};
