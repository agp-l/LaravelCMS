<?php
// 2026_06_11_170641_create_reservations_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
       Schema::create('reservations', function (Blueprint $table) {
    $table->id();
    $table->date('date');
    $table->date('date_end')->nullable();
    $table->string('recurring_days')->nullable(); // chybějící
    $table->json('slots');                       // chybějící
    $table->string('child_name');
    $table->integer('kids_count')->default(1);
    $table->text('child_info')->nullable();
    $table->string('parent_name');
    $table->string('contact');
    $table->text('note')->nullable();
    $table->string('custom_field_value', 1000)->nullable(); // chybějící
    $table->string('pricing_model');
    $table->string('sharing_type');
    $table->decimal('total_price', 8, 2);
    $table->string('payment_status')->default('pending');
    $table->foreignId('activity_id')->constrained()->onDelete('restrict');
    $table->timestamps();
});
    }

    public function down(): void
    {
        Schema::dropIfExists('reservations');
    }
};