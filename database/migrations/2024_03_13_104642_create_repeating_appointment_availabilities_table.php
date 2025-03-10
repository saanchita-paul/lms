<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRepeatingAppointmentAvailabilitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('repeating_appointment_availabilities', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('appointment_availability_id');
            $table->text('mon')->nullable();
            $table->text('tue')->nullable();
            $table->text('wed')->nullable();
            $table->text('thu')->nullable();
            $table->text('fri')->nullable();
            $table->text('sat')->nullable();
            $table->text('sun')->nullable();
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
        Schema::dropIfExists('repeating_appointment_availabilities');
    }
}
