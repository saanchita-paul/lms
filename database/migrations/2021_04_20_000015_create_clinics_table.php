<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClinicsTable extends Migration
{
    public function up()
    {
        Schema::create('clinics', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('clinic_name');
            $table->string('clinic_legal_name');
            $table->string('type');
            $table->string('dr_name');
            $table->longText('address');
            $table->string('email');
            $table->string('office_number');
            $table->string('hotline_phone_number')->nullable();
            $table->string('website')->nullable();
            $table->longText('specialty')->nullable();
            $table->longText('office_hours')->nullable();
            $table->longText('consultation_details_offers')->nullable();
            $table->longText('scheduling_hours')->nullable();
            $table->longText('emails_for_scheduling')->nullable();
            $table->longText('virtual_consultation')->nullable();
            $table->longText('services_offered_pricing')->nullable();
            $table->longText('financing')->nullable();
            $table->longText('insurance_details')->nullable();
            $table->boolean('medicaid')->default(0)->nullable();
            $table->boolean('medicare')->default(0)->nullable();
            $table->longText('doctor_specifics')->nullable();
            $table->longText('google_map_location')->nullable();
            $table->longText('covid_19_specifics')->nullable();
            $table->string('languages_spoken')->nullable();
            $table->longText('consult_email_patients')->nullable();
            $table->longText('reply_texts_patients')->nullable();
            $table->longText('extra_notes')->nullable();
            $table->string('slack_webhook_url')->nullable();
            $table->integer('callrail_company')->nullable();
            $table->string('twilio_number')->nullable();
            $table->string('twilio_subid')->nullable();
            $table->string('twilio_token')->nullable();
            $table->string('lead_center')->nullable();
            $table->string('status');
            $table->string('microsite_website')->nullable();
            $table->string('google_analytics')->nullable();
            $table->string('google_ads')->nullable();
            $table->string('facebook')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
