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
        if(!Schema::hasColumn('tables', 'stt')){
            Schema::table('tables', function (Blueprint $table) {
                $table->integer('stt');
            });
        }
        if(!Schema::hasColumn('tables', 'floor')){
            Schema::table('tables', function (Blueprint $table) {
                $table->integer('floor');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if(Schema::hasColumn('tables', 'stt')){
            Schema::table('tables', function (Blueprint $table) {
                $table->dropColumn('stt');
            });
        }
        if(Schema::hasColumn('tables', 'floor')){
            Schema::table('tables', function (Blueprint $table) {
                $table->dropColumn('floor');
            });
        }
    }
};
