<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSmtpToClinicsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('clinics', function (Blueprint $table) {
            $table->string('smtpUsername')->after('nurture_automation')->nullable();
            $table->string('smtpServer')->after('smtpUsername')->nullable();
            $table->string('smtpPort')->after('smtpServer')->nullable();
            $table->string('smtpPassword')->after('smtpPort')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('clinics', function (Blueprint $table) {
            $table->dropColumn('smtpUsername');
            $table->dropColumn('smtpServer');
            $table->dropColumn('smtpPort');
            $table->dropColumn('smtpPassword');
        });
    }
}
