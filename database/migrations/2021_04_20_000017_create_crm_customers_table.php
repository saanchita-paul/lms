<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCrmCustomersTable extends Migration
{
    public function up()
    {
        Schema::create('crm_customers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('address')->nullable();
            $table->longText('description')->nullable();
            $table->string('phone_form')->nullable();
            $table->string('three_plus_attempts')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->longText('form_data')->nullable();
            $table->string('form_id')->nullable();
            $table->datetime('consultation_booked_date')->nullable();
            $table->datetime('no_showed_date')->nullable();
            $table->boolean('convert_to_deal')->default(0)->nullable();
            $table->datetime('convert_deal_date')->nullable();
            $table->string('reason')->nullable();
            $table->decimal('value', 15, 2)->nullable();
            $table->string('deal_status')->nullable();
            $table->string('won_lost')->nullable();
            $table->datetime('won_lost_date')->nullable();
            $table->string('lead_type')->nullable();
            $table->string('source_other')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
