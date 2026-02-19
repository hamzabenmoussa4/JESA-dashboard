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
        Schema::create('echanges', function (Blueprint $table) {
            $table->id();
            $table->foreignId('interlocuteur_id')->constrained()->onDelete('cascade');
            $table->enum('type', ['appel', 'email', 'réunion']);
            $table->text('contenu');
            $table->dateTime('date_echange');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('echanges');
    }
};
