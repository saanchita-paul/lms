<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateManageClinicTemplate extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('manage_clinic_template', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('clinic_id');
            $table->string('dayinterval');            
            $table->longText('text_template')->nullable();
            $table->longText('email_subject')->nullable();
            $table->longText('email_template')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }
}