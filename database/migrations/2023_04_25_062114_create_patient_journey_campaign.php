<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePatientJourneyCampaign extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('patient_journey_campaign', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('dayinterval');
            $table->longText('text_template')->nullable();
            $table->longText('email_subject')->nullable();
            $table->longText('email_template')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('patient_journey_campaign');
    }
}
