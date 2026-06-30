<?php
// 2026_06_30_211859_add_monthly_pass_mode_to_activities_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
public function up() {
    Schema::table('activities', function (Blueprint $table) {
        $table->string('monthly_pass_mode', 50)->default('all_days')->after('pricing_model');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('activities', function (Blueprint $table) {
            //
        });
    }
};


