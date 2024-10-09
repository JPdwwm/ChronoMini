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
        Schema::create('kid_user', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            // clé étrangére kid_id et user_id

            $table->foreignId('kid_id')->constrained()->onDelete('cascade');

            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kid_user');
    }
};
