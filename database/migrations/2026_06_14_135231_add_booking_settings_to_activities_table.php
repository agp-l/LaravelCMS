<?php
// 2026_06_14_135231_add_booking_settings_to_activities_table.php
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
        Schema::table('activities', function (Blueprint $table) {
            // Kapacita a typ rezervace
            $table->integer('max_capacity')->default(5)->after('icon');
            $table->string('booking_mode')->default('both')->after('max_capacity')->comment('individual, shared, both');

            // Ceny a účtování
            $table->string('pricing_model')->default('hourly')->after('booking_mode')->comment('hourly, daily, monthly');
            $table->decimal('price_per_month', 10, 2)->nullable()->after('price_per_day');

            // Viditelnost políček formuláře (zda se mají vyžadovat/zobrazit)
            $table->boolean('show_child_name')->default(true)->after('pricing_model');
            $table->boolean('show_kids_count')->default(true)->after('show_child_name');
            $table->boolean('show_child_info')->default(true)->after('show_kids_count');
            $table->boolean('show_note')->default(true)->after('show_child_info');

            // Náhradní políčka pro budoucí rozšíření
            $table->string('custom_field_label')->nullable()->after('show_note');
            $table->boolean('custom_field_required')->default(false)->after('custom_field_label');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('activities', function (Blueprint $table) {
            $table->dropColumn([
                'max_capacity',
                'booking_mode',
                'pricing_model',
                'price_per_month',
                'show_child_name',
                'show_kids_count',
                'show_child_info',
                'show_note',
                'custom_field_label',
                'custom_field_required'
            ]);
        });
    }
};