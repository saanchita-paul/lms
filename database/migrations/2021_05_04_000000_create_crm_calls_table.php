<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCrmCallsTable extends Migration
{
    public function up()
    {
        Schema::create('crm_calls', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->longText('subject')->nullable();
            $table->string('type')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
