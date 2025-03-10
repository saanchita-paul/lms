<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCalendarTypeColumnToAppointmentAvailabilitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('appointment_availabilities', function (Blueprint $table) {
            $table->string('calendar_type')->after('clinic_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('appointment_availabilities', function (Blueprint $table) {

            $table->dropColumn('calendar_type');
        });
    }
}
