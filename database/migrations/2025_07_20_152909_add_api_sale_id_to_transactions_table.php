<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   // file: YYYY_MM_DD_xxxxxx_add_api_sale_id_to_transactions_table.php

public function up()
{
    Schema::table('transactions', function (Blueprint $table) {
        // Tambahkan kolom untuk menyimpan ID dari API.
        // `unique()` untuk memastikan tidak ada data ganda.
        $table->unsignedBigInteger('api_sale_id')->unique()->nullable()->after('id');
    });
}

public function down()
{
    Schema::table('transactions', function (Blueprint $table) {
        $table->dropColumn('api_sale_id');
    });
}
};
