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
        // Prototyp I: grundlegende Projekttabelle
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('projektname');
            $table->text('beschreibung')->nullable();
            $table->string('bearbeitungsstatus')->default('offen');
            $table->integer('mitglied');
            //prototyp 2?
            // $table->string('bildpfad')->nullable();
            $table->foreignId('ersteller_id') ->nullable()
            ->constrained('users')
            ->nullONDelete();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
