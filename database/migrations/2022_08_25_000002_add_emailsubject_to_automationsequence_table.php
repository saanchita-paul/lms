<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddEmailSubjectToAutomationsequenceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('automationsequence', function (Blueprint $table) {
             $table->longText('email_subject')->after('text_template')->nullable();
             
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('automationsequence', function (Blueprint $table) {
             $table->dropColumn('email_subject');
             
        });
    }
}