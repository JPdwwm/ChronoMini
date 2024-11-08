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
        Schema::create('time_breaks', function (Blueprint $table) {
            $table->id();
            $table->time('break_start');
            $table->time('break_end')->nullable();
            $table->float('total')->nullable();
            $table->timestamps();

            // clé étrangére record_id
            $table->foreignId('record_id')->constrained()->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('time_breaks');
    }
};
