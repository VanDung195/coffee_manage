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
        Schema::table('users', function (Blueprint $table) {
            if(!Schema::hasColumn('users', 'address')) {
                $table->string('address')->nullable()->after('phone');
            }
            if(!Schema::hasColumn('user', 'remember_token')){
                $table->string('remember_token')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if(Schema::hasColumn('users', 'adress')) {
                $table->dropColumn('address');
            }
            if(Schema::hasColumn('user', 'remember_token')){
                $table->dropColumn('remember_token');
            }
        });
    }
};
