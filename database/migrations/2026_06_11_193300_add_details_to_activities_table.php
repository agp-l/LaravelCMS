<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('activities', function (Blueprint $table) {
            // Přidáváme nové sloupce za sloupec 'icon'
            $table->decimal('price_per_hour', 8, 2)->default(0)->after('icon');
            $table->string('color_theme')->default('#0d6efd')->after('price_per_hour');
            $table->boolean('is_active')->default(true)->after('color_theme');
        });
    }

    public function down(): void
    {
        Schema::table('activities', function (Blueprint $table) {
            $table->dropColumn(['price_per_hour', 'color_theme', 'is_active']);
        });
    }
};