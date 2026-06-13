<?php

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
