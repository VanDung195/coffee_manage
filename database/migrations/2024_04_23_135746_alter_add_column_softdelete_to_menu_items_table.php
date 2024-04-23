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
        Schema::table('menu_items', function (Blueprint $table) {
            if(!Schema::hasColumn('menu_items', 'deleted_at')) {
                $table->softDeletes(); 
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('menu_items', function (Blueprint $table) {
            if(Schema::hasColumn('menu_items', 'deleted_at')) {
                $this->dropColumn('deleted_at');
            }
        });
    }
};
