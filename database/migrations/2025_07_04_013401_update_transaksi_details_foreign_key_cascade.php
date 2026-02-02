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
        Schema::table('transaksi_details', function (Blueprint $table) {
            // Drop the existing foreign key constraint
            $table->dropForeign(['barang_id']);
            
            // Add the new foreign key constraint with CASCADE DELETE
            $table->foreign('barang_id')
                  ->references('id')
                  ->on('barangs')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transaksi_details', function (Blueprint $table) {
            // Drop the cascade foreign key constraint
            $table->dropForeign(['barang_id']);
            
            // Restore the original foreign key constraint without cascade
            $table->foreign('barang_id')
                  ->references('id')
                  ->on('barangs');
        });
    }
};
