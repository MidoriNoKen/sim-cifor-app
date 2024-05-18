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
        Schema::table('leave_applications', function (Blueprint $table) {
            $table->string('accumulation')->change();
        });

        Schema::table('travel_authorisations', function (Blueprint $table) {
            $table->string('accumulation')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('leave_applications', function (Blueprint $table) {
            $table->integer('accumulation')->change();
        });

        Schema::table('travel_authorisations', function (Blueprint $table) {
            $table->integer('accumulation')->change();
        });
    }
};
