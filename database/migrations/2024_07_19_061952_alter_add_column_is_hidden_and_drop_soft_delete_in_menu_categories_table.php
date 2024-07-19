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
        Schema::table('menu_categories', function (Blueprint $table) {
            if(Schema::hasColumn('menu_categories', 'deleted_at')) {
                $table->dropSoftDeletes();
            }
            if(!Schema::hasColumn('menu_categories', 'is_hidden')) {
                $table->boolean('is_hidden')->after('name');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('menu_categories', function (Blueprint $table) {
            if(!Schema::hasColumn('menu_categories', 'deleted_at')) {
                $table->softDeletes();
            }
            if(Schema::hasColumn('menu_categories', 'is_hidden')) {
                $table->dropColumn('is_hidden');
            }
        });
    }
};
