<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddActivatePatientJourneyCampaignToClinicsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('clinics', function (Blueprint $table) {
            $table->string('patient_journey_campaign')->after('smtpMailer')->nullable();
            $table->longText('usertestimonials')->after('patient_journey_campaign')->nullable();
            $table->longText('listtechnology')->after('usertestimonials')->nullable();
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
            $table->dropColumn('patient_journey_campaign');
            $table->dropColumn('usertestimonials');
            $table->dropColumn('listtechnology');
        });
    }
}