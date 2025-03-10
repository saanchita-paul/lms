<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldToClinicsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('clinics', function (Blueprint $table) {
            $table->tinyInteger('professional_video')->nullable()->comment('No=0,Yes=1');
            $table->tinyInteger('quality_patient_after_photos')->nullable()->comment('No=0,Yes=1');
            $table->tinyInteger('google_my_business_review')->nullable()->comment('No=0,Yes=1');
            $table->text('paid_media')->nullable();
            $table->tinyInteger('promotional_specials')->nullable()->comment('No=0,Yes=1');
            $table->text('promotional_specials_desc')->nullable();
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
            //
        });
    }
}
