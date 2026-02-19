<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('meetings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnUpdate()
                ->cascadeOnDelete();              // propriétaire (utilisateur connecté)
            $table->string('meeting_name');       // Meeting Name
            $table->date('date_of_meeting');      // Date of meeting
            $table->time('time');                 // Time
            $table->string('prepared_by');        // Prepared by
            $table->string('location')->nullable(); // Location (optionnel)
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('meetings');
    }
};
