<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('travel_authorisations', function (Blueprint $table) {
            $table->dropColumn('accommodation_detail');
        });
    }

    public function down()
    {
        Schema::table('travel_authorisations', function (Blueprint $table) {
            $table->text('accommodation_detail')->nullable();
        });
    }
};