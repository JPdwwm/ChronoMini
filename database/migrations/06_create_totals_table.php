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
        Schema::create('totals', function (Blueprint $table) {
            $table->id();
            $table->float('monthly_total_hours');
            $table->float('monthly_total_euros');
            $table->tinyInteger('month');
            $table->integer('year');
            $table->timestamps();

            // clé étrangére kid_id 
            $table->foreignId('kid_id')->constrained();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('totals');
    }
};
