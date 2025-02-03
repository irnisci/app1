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
        Schema::create('mood_checkins', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('mood'); // Stimmung (z. B. "Glücklich", "Neutral", "Traurig")
            $table->text('note')->nullable(); // Optional: Nutzer kann eine Notiz hinterlassen
            $table->timestamp('checked_at'); // Zeitstempel des Check-ins
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mood_checkins');
    }
};
