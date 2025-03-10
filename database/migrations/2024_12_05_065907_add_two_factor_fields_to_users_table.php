<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTwoFactorFieldsToUsersTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Basic fields needed for both Email and SMS
            $table->boolean('two_factor_enabled')->default(false);
            $table->string('two_factor_type')->nullable()->comment('email, sms, or both'); // email, sms, or both
            $table->timestamp('two_factor_verified_at')->nullable();
            $table->string('two_factor_recovery_codes')->nullable();

            // SMS specific fields
            $table->boolean('phone_verified')->default(false);
            $table->timestamp('phone_verified_at_2fa')->nullable();

            // Email specific fields
            $table->boolean('email_verified')->default(false);
            $table->timestamp('email_verified_at_2fa')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'two_factor_enabled',
                'two_factor_type',
                'two_factor_verified_at',
                'two_factor_recovery_codes',
                'phone_verified',
                'phone_verified_at',
                'email_verified',
                'email_verified_at'
            ]);
        });
    }
}
