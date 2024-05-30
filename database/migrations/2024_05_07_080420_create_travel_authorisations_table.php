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
            $table->string('officer_id')->nullable();
            $table->text('officer_reject_reasons')->nullable();
            $table->string('hrManager_id')->nullable();
            $table->text('hrManager_reject_reasons')->nullable();
            $table->string('finance_id')->nullable();
            $table->text('finance_reject_reasons')->nullable();
            $table->string('unit_id');
            $table->string('transport_type');
            $table->datetime('start_date');
            $table->datetime('end_date');
            $table->integer('accumulation');
            $table->text('travel_reasons')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('travel_authorisations');
    }
}
