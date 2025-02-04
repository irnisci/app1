<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('user_progress', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Nutzer
            $table->foreignId('lesson_id')->constrained()->onDelete('cascade'); // Lektion
            $table->timestamp('completed_at')->nullable(); // Wann wurde die Lektion abgeschlossen?
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('user_progress');
    }
};
