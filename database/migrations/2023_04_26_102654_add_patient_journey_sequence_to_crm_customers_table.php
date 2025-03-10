<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPatientJourneySequenceToCrmCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('crm_customers', function (Blueprint $table) {
            
            $table->tinyInteger('patient_journey_automation')->after('sms_sequence')->default(0);
            $table->tinyInteger('patient_journey_email_sequence')->after('patient_journey_automation')->default(0);
            $table->tinyInteger('patient_journey_sms_sequence')->after('patient_journey_email_sequence')->default(0);
            $table->datetime('next_mail_date')->after('patient_journey_sms_sequence')->nullable();    
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('crm_customers', function (Blueprint $table) {
            $table->dropColumn('patient_journey_automation');
            $table->dropColumn('patient_journey_email_sequence');
            $table->dropColumn('patient_journey_sms_sequence');
            $table->dropColumn('next_mail_date');
        });
    }
}
