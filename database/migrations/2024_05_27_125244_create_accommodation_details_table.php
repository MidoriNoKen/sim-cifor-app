<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('accommodation_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('travel_authorisation_id');
            $table->string('name');
            $table->integer('quantity');
            $table->decimal('price', 10, 0);
            $table->decimal('total_price', 10, 0)->default(0);
            $table->text('description')->nullable();
            $table->timestamps();
            $table->foreign('travel_authorisation_id')->references('id')->on('travel_authorisations')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('accommodation_details');
    }
};
