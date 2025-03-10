<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDailySummaryNotificationAndWeeklySummaryNotificationToSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->boolean('daily_summary_email_notification')->after('appointment_browser_notification')->default(true);
            $table->boolean('weekly_summary_email_notification')->after('daily_summary_email_notification')->default(true);
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
            $table->dropColumn('daily_summary_email_notification');
            $table->dropColumn('weekly_summary_email_notification');
        });
    }
}
