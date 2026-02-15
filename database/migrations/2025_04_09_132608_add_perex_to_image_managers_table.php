<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('image_managers', function (Blueprint $table) {
            $table->text('perex')->nullable()->after('title');
        });
    }
    
    public function down()
    {
        Schema::table('image_managers', function (Blueprint $table) {
            $table->dropColumn('perex');
        });
    }
    
};
