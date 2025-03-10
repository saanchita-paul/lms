<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLeadMovedToFollowUpColumnToSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->boolean('follow_up_email_notification')->after('appointment_browser_notification')->default(true);
            $table->boolean('follow_up_text_notification')->after('follow_up_email_notification')->default(true);
            $table->boolean('follow_up_browser_notification')->after('follow_up_text_notification')->default(true);
            $table->dropColumn('lead_email_notification');
            $table->dropColumn('lead_text_notification');
            $table->dropColumn('lead_browser_notification');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('follow_up_column_to_settings', function (Blueprint $table) {
            $table->dropColumn('follow_up_email_notification');
            $table->dropColumn('follow_up_text_notification');
            $table->dropColumn('follow_up_browser_notification');
        });
    }
}
