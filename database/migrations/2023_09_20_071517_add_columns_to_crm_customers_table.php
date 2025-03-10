<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToCrmCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('crm_customers', function (Blueprint $table) {
            $table->integer('lead_score')->nullable()->after('patient_journey_sms_sequence');
            $table->longText('call_summary')->nullable()->after('lead_score');
            $table->longText('full_transcript')->nullable()->after('call_summary');
            $table->integer('phone_score')->nullable()->after('full_transcript');
            $table->integer('email_score')->nullable()->after('phone_score');
            $table->integer('name_score')->nullable()->after('email_score');
            $table->longText('trustfull_details')->nullable()->after('name_score');
            $table->longText('callrail_details')->nullable()->after('trustfull_details');
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
            //
        });
    }
}
