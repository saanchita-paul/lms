<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateClinicsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('clinics', function (Blueprint $table) {
            $table->string('provider_ids')->after('location')->nullable();
            $table->string('operatory_id')->after('provider_ids')->nullable();
            $table->integer('appointment_duration')->after('operatory_id')->nullable();
            $table->string('clinic_logo')->after('clinic_legal_name')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('clinics', function (Blueprint $table) {
            $table->dropColumn('provider_ids');
            $table->dropColumn('operatory_id');
            $table->dropColumn('appointment_duration');
            $table->dropColumn('clinic_logo');
        });
    }
}
