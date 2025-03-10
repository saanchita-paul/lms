<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSubdomainlocationtable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('clinics', function (Blueprint $table) {
            $table->boolean('nexhealthselection')->after('reportrecipients')->default(1);
            $table->string('subdomain')->after('nexhealthselection');
            $table->string('location')->after('subdomain');           
            $table->string('nexhealthkey')->after('location');           
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
             $table->boolean('nexhealthselection')->default(1);
             $table->string('subdomain');
             $table->string('location');
             $table->string('nexhealthkey');
        });
    }
}


