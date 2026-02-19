<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('minutes', function (Blueprint $table) {
            $table->id();

            // propriétaire de l'enregistrement (comme tes autres ressources)
            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            // lien vers un meeting existant
            $table->foreignId('meeting_id')
                ->constrained('meetings')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            // contenu de la minute
            $table->text('topic_idea_decision');   // compte rendu
            $table->string('responsible');         // personne responsable
            $table->date('due_date');              // date d'échéance

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('minutes');
    }
};
