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
        Schema::table('barangs', function (Blueprint $table) {
            // Drop the existing foreign key constraint
            $table->dropForeign(['kategori_id']);
            
            // Add the new foreign key constraint with CASCADE DELETE
            $table->foreign('kategori_id')
                  ->references('id')
                  ->on('kategori_barangs')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('barangs', function (Blueprint $table) {
            // Drop the cascade foreign key constraint
            $table->dropForeign(['kategori_id']);
            
            // Restore the original foreign key constraint without cascade
            $table->foreign('kategori_id')
                  ->references('id')
                  ->on('kategori_barangs');
        });
    }
};
