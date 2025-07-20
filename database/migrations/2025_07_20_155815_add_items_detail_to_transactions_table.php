<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   // YYYY_MM_DD_xxxxxx_add_items_detail_to_transactions_table.php

public function up()
{
    Schema::table('transactions', function (Blueprint $table) {
        // Menambahkan kolom untuk menyimpan detail item sebagai JSON
        $table->json('items_detail')->nullable()->after('keterangan');
    });
}

public function down()
{
    Schema::table('transactions', function (Blueprint $table) {
        $table->dropColumn('items_detail');
    });
}
};
