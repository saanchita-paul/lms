<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDoctorBiographyFieldsToClinicsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('clinics', function (Blueprint $table) {
            $table->enum('doctors_biography', ['Yes', 'No'])->default('No')->after('call_transcript_api');
            $table->longText('doctors_biography_description')->nullable()->after('doctors_biography');
            
            $table->enum('doctors_biography_summary', ['Yes', 'No'])->default('No')->after('doctors_biography_description');
            $table->longText('doctors_biography_summary_description')->nullable()->after('doctors_biography_summary');
            
            $table->enum('marketing_message', ['Yes', 'No'])->default('No')->after('doctors_biography_summary_description');
            $table->longText('marketing_message_description')->nullable()->after('marketing_message');
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
            $table->dropColumn([
                'doctors_biography',
                'doctors_biography_description',
                'doctors_biography_summary',
                'doctors_biography_summary_description',
                'marketing_message',
                'marketing_message_description',
            ]);
        });
    }
}
