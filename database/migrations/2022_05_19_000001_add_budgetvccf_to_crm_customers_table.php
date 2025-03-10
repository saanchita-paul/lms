<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBudgetvccfToCrmCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('crm_customers', function (Blueprint $table) {
             $table->longText('budget')->after('ccapture')->nullable();
             $table->string('verbal_confirmation')->after('budget')->nullable();
             $table->string('informed_consult_fee')->after('verbal_confirmation')->nullable();
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
             $table->dropColumn('budget');
             $table->dropColumn('verbal_confirmation');
             $table->dropColumn('informed_consult_fee');
        });
    }
}
