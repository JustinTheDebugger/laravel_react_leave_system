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
        Schema::table('leave_applications', function (Blueprint $table) {
            $table->boolean('is_half_day')->default(false);
            $table->enum('half_day_period', ['AM', 'PM'])->nullable(); // only needed if half-day
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('leave_applications', function (Blueprint $table) {
            //
        });
    }
};
