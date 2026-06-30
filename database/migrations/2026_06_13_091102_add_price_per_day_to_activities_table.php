<?php
// 2026_06_13_091102_add_price_per_day_to_activities_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
public function up()
{
    Schema::table('activities', function (Blueprint $table) {
        $table->integer('price_per_day')->default(0)->after('price_per_hour');
    });
}

public function down()
{
    Schema::table('activities', function (Blueprint $table) {
        $table->dropColumn('price_per_day');
    });
}
};
