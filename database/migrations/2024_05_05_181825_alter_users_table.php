<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema; // Add this line

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('role_id')->after('id');
            $table->foreignId('supervisor_id')->after('id')->nullable();
            $table->foreignId('manager_id')->after('id')->nullable();
            $table->string('position');
            $table->date('born_date');
            $table->foreign('role_id')->references('id')->on('roles');
            $table->foreign('supervisor_id')->references('id')->on('users')->nullable();
            $table->foreign('manager_id')->references('id')->on('users')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['role_id']);
            $table->dropForeign(['supervisor_id']);
            $table->dropForeign(['manager_id']);
            $table->dropColumn('role_id');
            $table->dropColumn('position');
            $table->dropColumn('born_date');
            $table->dropColumn('supervisor_id');
            $table->dropColumn('manager_id');
        });
    }
};