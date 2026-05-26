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
        Schema::create('poll_votes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('discussion_answer_id')
                ->constrained('discussion_answers')
                ->onDelete('cascade');
            $table->foreignId('user_id')
                ->constrained('users')
                ->onDelete('cascade');
            // KORREKTUR: foreignId verwendet und String-Anführungszeichen geschlossen
            $table->foreignId('poll_option_id')
                ->constrained('poll_options')
                ->onDelete('cascade');

            $table->timestamps();

            // Verhindert, dass ein User beim selben Beitrag (Umfrage) mehrfach abstimmt:
            $table->unique(['discussion_answer_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // KORREKTUR: Richtigen Tabellennamen beim Rollback löschen
        Schema::dropIfExists('poll_votes');
    }
};