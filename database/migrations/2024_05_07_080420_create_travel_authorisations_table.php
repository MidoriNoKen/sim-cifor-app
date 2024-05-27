<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTravelAuthorisationsTable extends Migration
{
    public function up()
    {
        Schema::create('travel_authorisations', function (Blueprint $table) {
            $table->id();
            $table->string('applicant_id');
            $table->string('status');
            $table->string('supervisor_id')->nullable();
            $table->text('supervisor_reject_reasons')->nullable();
            $table->string('manager_id')->nullable();
            $table->text('manager_reject_reasons')->nullable();
            $table->string('finance_id')->nullable();
            $table->text('finance_reject_reasons')->nullable();
            $table->string('unit_id');
            $table->string('transport_type');
            $table->datetime('start_date');
            $table->datetime('end_date');
            $table->integer('accumulation');
            $table->text('accommodation_detail')->nullable();
            $table->text('travel_reasons')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('travel_authorisations');
    }
}