<?php
// 2026_06_10_174109_create_layout_overrides_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('layout_overrides', function (Blueprint $table) {
            $table->id();
            $table->string('path_pattern', 255);
            $table->string('layout', 255);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('layout_overrides');
    }
};