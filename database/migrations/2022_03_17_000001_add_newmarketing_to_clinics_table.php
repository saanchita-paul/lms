<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNewmarketingToClinicsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('clinics', function (Blueprint $table) {
             $table->string('hero_image')->after('primary_selling_other')->nullable();
             $table->string('docto_bio')->after('hero_image')->nullable();
             $table->string('testimonials')->after('docto_bio')->nullable();
             $table->string('google_map')->after('testimonials')->nullable();
             $table->string('patient_photos')->after('google_map')->nullable();
             $table->longText('technology')->after('patient_photos')->nullable();
             $table->longText('available_treatment')->after('technology')->nullable();
             $table->longText('available_treatment_other')->after('available_treatment')->nullable();
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
            $table->dropColumn('hero_image');
            $table->dropColumn('docto_bio');
            $table->dropColumn('testimonials');
            $table->dropColumn('google_map');
            $table->dropColumn('patient_photos');
            $table->dropColumn('technology');
            $table->dropColumn('available_treatment');
            $table->dropColumn('available_treatment_other');
        });
    }
}
