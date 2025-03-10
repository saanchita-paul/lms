<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddImmediateSmsAndImmediateMailToClinicsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('clinics', function (Blueprint $table) {
            $table->boolean('immediate_sms')->default(false)->after('autosmstext'); // Replace 'existing_column_name' with the actual column after which you want to add the new columns
            $table->boolean('immediate_mail')->default(false)->after('immediate_sms');
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
            $table->dropColumn('immediate_sms');
            $table->dropColumn('immediate_mail');
        });
    }
}
