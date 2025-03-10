<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->boolean('lead_email_notification')->default(true);
            $table->boolean('lead_text_notification')->default(true);
            $table->boolean('lead_browser_notification')->default(true);
            $table->boolean('appointment_email_notification')->default(true);
            $table->boolean('appointment_text_notification')->default(true);
            $table->boolean('appointment_browser_notification')->default(true);
            $table->boolean('do_not_disturb')->default(false);
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
        Schema::dropIfExists('setting_user');
    }
}
