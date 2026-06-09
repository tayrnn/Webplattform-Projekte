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
        Schema::create('projekt_kategorie', function (Blueprint $table) {
            $table->id();
            $table->foreignId('projekt_id')->constrained('projects')->cascadeOnDelete();
            $table->foreignId('kategorie_id')->constrained('categories')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projekt_kategorie');
    }
};
