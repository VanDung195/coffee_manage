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
        Schema::create('salary_information', function (Blueprint $table) {
            $table->id();
            $table->date('payroll_date')->nullable();
            $table->integer('work_hours')->default('0');
            $table->double('total_amount')->default('0');
            $table->double('bonus')->nullable();
            $table->double('penalties')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('salary_information');
    }
};
