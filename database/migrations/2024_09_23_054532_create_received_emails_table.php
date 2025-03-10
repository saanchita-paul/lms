<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReceivedEmailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('received_emails', function (Blueprint $table) {
            $table->id();
            $table->integer('clinic_id')->nullable();
            $table->string('email_token')->nullable();
            $table->dateTime('email_created_date')->nullable();
            $table->boolean('email_read')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('received_emails');
    }
}
