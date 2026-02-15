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
        Schema::create('layout_overrides', function (Blueprint $table) {
            $table->id();
            $table->string('path_pattern'); // např. cs/clanek/*
            $table->string('layout');       // např. layouts.mizzle.app
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('layout_overrides');
    }
};
