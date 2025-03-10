<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddEmailSequenceSmsSequenceToCrmCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('crm_customers', function (Blueprint $table) {
             $table->tinyInteger('email_sequence')->after('automation')->default(0);
             $table->tinyInteger('sms_sequence')->after('email_sequence')->default(0);
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
             $table->dropColumn('email_sequence');
             $table->dropColumn('sms_sequence');
        });
    }
}