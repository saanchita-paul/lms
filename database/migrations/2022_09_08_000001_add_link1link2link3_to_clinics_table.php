<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLink1Link2Link3ToClinicsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('clinics', function (Blueprint $table) {
             $table->string('link1')->after('nurture_automation')->nullable();
             $table->string('link2')->after('link1')->nullable();
             $table->string('link3')->after('link2')->nullable();
             
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
            $table->dropColumn('link1');
            $table->dropColumn('link2');
            $table->dropColumn('link3');
        });
    }
}
