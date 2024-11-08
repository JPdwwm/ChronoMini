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
        Schema::create('records', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->time('drop_hour');
            $table->time('pick_up_hour')->nullable();
            $table->float('amount_hours')->nullable();
            $table->tinyInteger('drop_status')->nullable();
            $table->tinyInteger('pick_up_status')->nullable();
            $table->date('date');
            

            // clé étrangére kid_id et user_id
            $table->foreignId('kid_id')->constrained();
            $table->foreignId('user_id')->constrained();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('records');
    }
};
