<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLeadReconnectingNotificationsColumnToSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->boolean('lead_reconnecting_email_notification')->after('lead_browser_notification')->default(true);
            $table->boolean('lead_reconnecting_text_notification')->after('lead_reconnecting_email_notification')->default(true);
            $table->boolean('lead_reconnecting_browser_notification')->after('lead_reconnecting_text_notification')->default(true);
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
            $table->dropColumn('lead_reconnecting_email_notification');
            $table->dropColumn('lead_reconnecting_text_notification');
            $table->dropColumn('lead_reconnecting_browser_notification');
        });
    }
}
