<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLeaveApplicationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('leave_applications', function (Blueprint $table) {
            $table->id();
            $table->string('applicant_id');
            $table->string('status');

            // officer Status and Notes
            $table->string('officer_id')->nullable();
            $table->text('officer_reject_reasons')->nullable();

            // HR Status and Notes
            $table->string('hrManager_id')->nullable();
            $table->text('hrManager_reject_reasons')->nullable();

            // Leave Information
            $table->datetime('start_date');
            $table->datetime('end_date');
            $table->string('accumulation');
            $table->string('leave_type');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('leave_applications');
    }
}
