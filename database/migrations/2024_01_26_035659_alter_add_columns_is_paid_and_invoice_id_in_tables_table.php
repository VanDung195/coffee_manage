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
        Schema::table('tables', function (Blueprint $table) {
            if(!Schema::hasColumn('tables', 'invoice_id')){
                $table->bigInteger('invoice_id')->after('status');
            }
            if(!Schema::hasColumn('tables', 'is_paid')){
                $table->boolean('is_paid')->after('invoice_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tables', function (Blueprint $table) {
            if(Schema::hasColumn('tables', 'invoice_id')){
                $table->bigInteger('invoice_id');
            }
            if(Schema::hasColumn('tables', 'is_paid')){
                $table->dropColumn('is_paid');
            }
        });
    }
};
