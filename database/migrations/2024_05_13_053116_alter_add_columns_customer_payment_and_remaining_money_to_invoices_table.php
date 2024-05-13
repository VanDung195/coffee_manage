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
            if(!Schema::hasColumn('invoices', 'customer_payment')) {
                $table->float('customer_payment')->nullable()->after('total_price'); 
            }
            if(!Schema::hasColumn('invoices', 'remaining_money'))
            {
                $table->float('remaining_money')->nullable()->after('customer_payment');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            if(Schema::hasColumn('invoices', 'customer_payment'))
            {
                $table->dropColumn('customer_payment');
            }
            if(Schema::hasColumn('invoices', 'remaining_money'))
            {
                $table->dropColumn('remaining_money');
            }
        });
    }
};
