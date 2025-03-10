<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCrmCustomerCallDetailsTable extends Migration
{

    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('crm_customer_call_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('crm_customer_id');
            $table->longText('call_summary')->nullable();
            $table->longText('full_transcript')->nullable();
            $table->longText('audio_file_url')->nullable();
            $table->longText('callrail_details')->nullable();
            $table->timestamp('callrail_time')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('crm_customer_call_details');
    }
}
