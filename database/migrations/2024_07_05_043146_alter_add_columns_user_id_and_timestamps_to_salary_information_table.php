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
            if(!Schema::hasColumn('salary_information', 'user_id')) {
                // $table->foreignId('user_id')->constrained();
                $table->unsignedBigInteger('user_id')->after('penalties');
                $table->foreign('user_id')->references('id')->on('users');
                $table->timestamps();
            }
            // if(Schema::hasColumn('users', 'role'))
            // {
            //     $table->unsignedBigInteger('role')->change();
            //     $table->foreign('role')->references('id')->on('positions');
            // }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('salary_information', function (Blueprint $table) {
            if(Schema::hasColumn('salary_information', 'user_id')) {
                // $table->dropForeign('user_id');
                $table->dropColumn('user_id');
            }
        });
    }
};
