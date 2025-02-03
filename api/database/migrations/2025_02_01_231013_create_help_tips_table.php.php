<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('help_tips', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('topic');
            $table->text('tip');
            $table->integer('rating')->nullable(); // Bewertung (1-5 Sterne)
            $table->timestamps();
        });
    }

    public function down() {
        Schema::dropIfExists('help_tips');
    }
};
