<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddWherebyNotificationToSettings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->boolean('whereby_email_notification')->default(true);
            $table->boolean('whereby_text_notification')->default(true);
            $table->boolean('whereby_browser_notification')->default(true);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('settings', function (Blueprint $table) {
           $table->dropColumn('whereby_email_notification');
           $table->dropColumn('whereby_text_notification');
           $table->dropColumn('whereby_browser_notification');
        });
    }
}
