<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateClinicPrimaryInfoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('clinics', function (Blueprint $table) {
            $table->text('primary_doctor_firstname')->nullable()->after('dr_abbreviations');
            $table->text('primary_doctor_lastname')->nullable()->after('primary_doctor_firstname');
            $table->text('primary_doctor_email')->nullable()->after('primary_doctor_lastname');
            $table->text('primary_doctor_phone')->nullable()->after('primary_doctor_email');
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
            $table->dropColumn('primary_doctor_firstname');
            $table->dropColumn('primary_doctor_lastname');
            $table->dropColumn('primary_doctor_email');
            $table->dropColumn('primary_doctor_phone');
        });
    }
}
