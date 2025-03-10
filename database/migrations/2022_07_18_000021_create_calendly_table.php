<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCalendlyTable extends Migration
{
    public function up()
    {
        Schema::create('calendly', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('crm_id');
            $table->longText('cdata')->nullable();
            $table->string('event_type');
            $table->bigInteger('lead_id')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
