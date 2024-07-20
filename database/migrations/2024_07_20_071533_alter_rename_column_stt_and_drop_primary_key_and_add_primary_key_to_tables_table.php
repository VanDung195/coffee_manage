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
            if(Schema::hasColumn('tables', 'stt')) {
                $table->renameColumn('stt', 'id');
            }
            if(Schema::hasColumn('tables', 'name')) {
                $table->dropPrimary('name');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tables', function (Blueprint $table) {
            if(Schema::hasColumn('tables', 'stt')) {
                $table->renameColumn('id', 'stt');
            }
            if(Schema::hasColumn('tables', 'name')) {
                $table->primary('name');
            }
        });
    }
};
