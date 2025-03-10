<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyColumnEmailScoreToEmailCode4DigitsOnCrmCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('crm_customers', function (Blueprint $table) {
            $table->renameColumn('email_score', 'email_code');
            $table->renameColumn('phone_score', 'phone_code');
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
            $table->renameColumn('email_code', 'email_score');
            $table->renameColumn('phone_code', 'phone_score');
        });
    }
}
