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
        Schema::table('invoices', function (Blueprint $table) {
            if(Schema::hasColumn('invoices', 'customer_payment')) {
                //12 chu so dau và 2 chứ số thập phân
                $table->double('customer_payment', 12, 2)->nullable()->change(); 
            }
            if(Schema::hasColumn('invoices', 'remaining_money')) {
                //12 chu so dau và 2 chứ số thập phân
                $table->double('remaining_money', 12, 2)->nullable()->change(); 
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            //
        });
    }
};
