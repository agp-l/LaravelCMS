<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();
            $table->date('date'); // Den rezervace
            $table->json('slots'); // Ulozi vybrane hodiny jako pole, napr. ["08:00 - 09:00", "09:00 - 10:00"]
            
            // Udaje o diteti
            $table->string('child_name');
            $table->integer('kids_count')->default(1);
            $table->text('child_info')->nullable();
            
            // Udaje o rodici
            $table->string('parent_name');
            $table->string('contact');
            $table->text('note')->nullable();
            
            // Parametry ceny a sdileni
            $table->string('pricing_model'); // Parťák na hodinu / Celodenní
            $table->string('sharing_type'); // Sdílený čas / Individuální čas
            $table->decimal('total_price', 8, 2);
            $table->string('payment_status')->default('pending'); // pending, paid, cancelled
            
            // Hlavni vybrana aktivita
            $table->foreignId('activity_id')->constrained()->onDelete('restrict');
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reservations');
    }
};