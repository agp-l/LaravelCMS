<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('timeline_posts', function (Blueprint $table) {
            $table->id();
            $table->dateTime('created_at'); // Používáme tvůj formát data
            $table->string('icon_class');
            $table->text('content');
            $table->text('map_url')->default('none');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('timeline_posts');
    }
};