<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddOnboardingToClinicsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('clinics', function (Blueprint $table) {
             $table->string('dr_fullname')->after('dr_name')->nullable();
             $table->string('dr_abbreviations')->after('dr_fullname')->nullable();
             $table->longText('multiple_doctors')->after('dr_abbreviations')->nullable();
             $table->longText('practice_specialty')->after('multiple_doctors')->nullable();
             $table->string('multi_specialty_other')->after('practice_specialty')->nullable();
             $table->longText('primary_services')->after('multi_specialty_other')->nullable();
             $table->string('primary_services_other')->after('primary_services')->nullable();
             $table->longText('town_state_zip')->after('primary_services_other')->nullable();
             $table->longText('form_notification_email')->after('town_state_zip')->nullable();
             $table->longText('holidays')->after('form_notification_email')->nullable();
             $table->string('current_website_accurate')->after('holidays')->nullable();
             $table->longText('needs_updated')->after('current_website_accurate')->nullable();
             $table->string('website_type')->after('needs_updated')->nullable();
             $table->string('area')->after('website_type')->nullable();
             $table->longText('primary_selling')->after('area')->nullable();
             $table->string('primary_selling_other')->after('primary_selling')->nullable();
             $table->longText('practice_different')->after('primary_selling_other')->nullable();
             $table->longText('another_company')->after('practice_different')->nullable();
             $table->string('full_arch_price')->after('another_company')->nullable();
             $table->string('overdenture_price')->after('full_arch_price')->nullable();
             $table->string('single_implant_price')->after('overdenture_price')->nullable();
             $table->string('consultation')->after('single_implant_price')->nullable();
             $table->string('consultation_price')->after('consultation')->nullable();
             $table->longText('financing_options')->after('consultation_price')->nullable();
             $table->string('financing_options_other')->after('financing_options')->nullable();
             $table->string('location_specifics')->after('financing_options_other')->nullable();
             $table->string('appointment_confirmations')->after('location_specifics')->nullable();
             $table->string('primary_treatment_coordinator')->after('appointment_confirmations')->nullable();
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
            $table->dropColumn('dr_fullname');
            $table->dropColumn('dr_abbreviations');
            $table->dropColumn('multiple_doctors');
            $table->dropColumn('practice_specialty');
            $table->dropColumn('multi_specialty_other');
            $table->dropColumn('primary_services');
            $table->dropColumn('primary_services_other');
            $table->dropColumn('town_state_zip');
            $table->dropColumn('form_notification_email');
            $table->dropColumn('holidays');
            $table->dropColumn('current_website_accurate');
            $table->dropColumn('needs_updated');
            $table->dropColumn('website_type');
            $table->dropColumn('area');
            $table->dropColumn('primary_selling');
            $table->dropColumn('primary_selling_other');
            $table->dropColumn('practice_different');
            $table->dropColumn('another_company');
            $table->dropColumn('full_arch_price');
            $table->dropColumn('overdenture_price');
            $table->dropColumn('single_implant_price');
            $table->dropColumn('consultation');
            $table->dropColumn('consultation_price');
            $table->dropColumn('financing_options');
            $table->dropColumn('location_specifics');
            $table->dropColumn('appointment_confirmations');
            $table->dropColumn('primary_treatment_coordinator');

        });
    }
}
