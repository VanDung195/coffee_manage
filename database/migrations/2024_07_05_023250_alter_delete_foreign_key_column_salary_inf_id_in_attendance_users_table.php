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
        Schema::table('attendance_users', function (Blueprint $table) {
            if(Schema::hasColumn('attendance_users', 'salary_inf_id')) {
                $table->dropForeign(['salary_inf_id']);
                $table->dropColumn('salary_inf_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('attendance_users', function (Blueprint $table) {
            //
        });
    }
};
