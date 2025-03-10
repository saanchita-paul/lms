<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAutomationSequenceTable extends Migration
{
    public function up()
    {
        Schema::create('automationsequence', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('dayinterval');
            $table->longText('text_template')->nullable();
            $table->longText('email_template')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
