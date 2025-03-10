<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyColumnSourceFromStringToIntegerOnZohoAnalyticsCostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('zoho_analytics_costs', function (Blueprint $table) {
            $table->integer('source')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('zoho_analytics_costs', function (Blueprint $table) {
            $table->string('source')->change();
        });
    }
}
