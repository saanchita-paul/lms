<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCrmCustomerUserPivotTable extends Migration
{
    public function up()
    {
        Schema::create('crm_customer_user', function (Blueprint $table) {
            $table->unsignedBigInteger('crm_customer_id');
            $table->foreign('crm_customer_id', 'crm_customer_id_fk_3722405')->references('id')->on('crm_customers')->onDelete('cascade');
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id', 'user_id_fk_3722405')->references('id')->on('users')->onDelete('cascade');
        });
    }
}
