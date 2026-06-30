<?php
// 2026_06_10_174108_create_image_managers_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('image_managers', function (Blueprint $table) {
            $table->id();
            $table->string('title', 255)->nullable();
            $table->text('perex')->nullable();
            $table->string('group', 255)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('image_managers');
    }
};