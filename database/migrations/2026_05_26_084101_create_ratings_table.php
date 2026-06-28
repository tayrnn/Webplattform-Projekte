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
        Schema::create('ratings', function (Blueprint $table) {
            $table->id();

            $table->foreignId('projekt_id')->constrained('projects')->cascadeOnDelete();
            $table->foreignId('nutzer_id')->constrained('users')->cascadeOnDelete();

            $table->unsignedTinyInteger('sterne');
            $table->text('kommentar')->nullable();

            $table->timestamps();

            $table->unique(['projekt_id', 'nutzer_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ratings');
    }
};