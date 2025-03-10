<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCustomUrlsToClinicsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('clinics', function (Blueprint $table) {
             $table->string('marketingdashboardurl')->after('facebook')->nullable();
             $table->string('schedulemeetingurl')->after('marketingdashboardurl')->nullable();
             $table->string('salestrainingurl')->after('schedulemeetingurl')->nullable();
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
             $table->dropColumn('marketingdashboardurl');
             $table->dropColumn('schedulemeetingurl');
             $table->dropColumn('salestrainingurl');
        });
    }
}
