<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCrmChatsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('crm_chats', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('lead_id');
            $table->integer('user_id');
            $table->tinyInteger('inbound')->default(0);
            $table->string('platform')->default('sms');
            $table->text('chat');
            $table->string('from')->nullable();
            $table->string('to')->nullable();
            $table->tinyInteger('delivered')->default(0);
            $table->tinyInteger('read')->default(0);
            $table->tinyInteger('is_sms')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    
}
