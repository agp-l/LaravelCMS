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
        Schema::create('menus', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('type')->default('custom'); // 'page', 'article', 'custom'
            $table->unsignedBigInteger('related_id')->nullable(); // id článku/stránky
            $table->string('url')->nullable(); // pro externí odkazy
            $table->boolean('published')->default(true);
            $table->unsignedInteger('order')->default(0);
            $table->unsignedBigInteger('parent_id')->nullable(); // pro dropdowny
            $table->timestamps();
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('menus');
    }

    
};
