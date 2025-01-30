<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('journals', function (Blueprint $table) {
            $table->string('emotion')->nullable(); // Speichert den ausgewählten Smiley
        });
    }

    public function down()
    {
        Schema::table('journals', function (Blueprint $table) {
            $table->dropColumn('emotion');
        });
    }
};
