<?php
// 2026_06_11_170523_create_activities_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('activities', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Napr. "Kurzy programování"
            $table->string('slug')->unique(); // Napr. "programovani"
            $table->text('description')->nullable();
            $table->string('icon')->nullable(); // Napr. "fa-code"
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('activities');
    }
};