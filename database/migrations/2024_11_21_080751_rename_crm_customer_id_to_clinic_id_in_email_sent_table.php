<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenameCrmCustomerIdToClinicIdInEmailSentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('email_sent', function (Blueprint $table) {
            $table->renameColumn('crm_customer_id', 'clinic_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('email_sent', function (Blueprint $table) {
            $table->renameColumn('clinic_id', '');
        });
    }
}
