<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateClinicSikkasoftkeyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('clinics', function (Blueprint $table) {
            $table->text('sikka_soft_app_id')->after('nexhealthkey');
            $table->text('sikka_soft_app_key')->after('sikka_soft_app_id');
            $table->text('sikka_soft_office_id')->after('sikka_soft_app_key');
            $table->text('sikka_soft_secret_key')->after('sikka_soft_office_id');
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
            $table->dropColumn('sikka_soft_app_id');
            $table->dropColumn('sikka_soft_app_key');
            $table->dropColumn('sikka_soft_office_id');
            $table->dropColumn('sikka_soft_secret_key');
        });
    }
}
