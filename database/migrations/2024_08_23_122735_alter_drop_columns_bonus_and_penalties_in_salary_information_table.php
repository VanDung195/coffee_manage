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
        Schema::table('salary_information', function (Blueprint $table) {
            if(Schema::hasColumn('salary_information', 'bonus')) {
                $table->dropColumn('bonus');
            }
            if(Schema::hasColumn('salary_information', 'penalties')) {
                $table->dropColumn('penalties');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('salary_information', function (Blueprint $table) {
            if(!Schema::hasColumn('salary_information', 'bonus')) {
                $table->double('bonus')->nullable();
            }
            if(!Schema::hasColumn('salary_information', 'penalties')) {
                $table->double('penalties')->nullable();
            }
        });
    }
};
