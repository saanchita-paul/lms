<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCallrailTable extends Migration
{
    public function up()
    {
        Schema::create('callrail', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('cr_company_id');
            $table->longText('jdata')->nullable();
            $table->string('lms_clinic_id')->nullable();
            $table->string('event_type');
            $table->boolean('lead_convert')->default(0)->nullable();
            $table->bigInteger('lead_id')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
