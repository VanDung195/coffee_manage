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
        if(!Schema::hasColumn('invoices', 'table_id')){
            Schema::table('invoices', function (Blueprint $table) {
                $table->string('table_id');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if(Schema::hasColumn('invoices', 'table_id')){
            Schema::table('invoices', function (Blueprint $table) {
                $table->dropColumn('table_id');
            });
        }
    }
};
