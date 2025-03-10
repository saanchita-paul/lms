<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMediabudgetmultiplelocationToClinicsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('clinics', function (Blueprint $table) {
             $table->string('media_budget')->after('other_marketing_notes')->nullable();
             $table->string('multiple_locations')->after('holidays')->nullable();
             $table->string('marketing_multiple_locations')->after('multiple_locations')->nullable();
             $table->longText('multiple_localtion_details')->after('marketing_multiple_locations')->nullable();
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
            $table->dropColumn('media_budget');
            $table->dropColumn('multiple_locations');
            $table->dropColumn('marketing_multiple_locations');
            $table->dropColumn('multiple_localtion_details');
        });
    }
}
