<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenameSlackWebhookUrlToDomainVerificationInClinicsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Rename the column first
        Schema::table('clinics', function (Blueprint $table) {
            $table->renameColumn('slack_webhook_url', 'domain_verification');
        });

        // Then change the column type and set the default value
        Schema::table('clinics', function (Blueprint $table) {
            $table->string('domain_verification')->default('Verify')->change();
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Change the column type back and remove the default value
        Schema::table('clinics', function (Blueprint $table) {
            $table->string('domain_verification')->default(null)->change();
        });

        // Then rename the column back
        Schema::table('clinics', function (Blueprint $table) {
            $table->renameColumn('domain_verification', 'slack_webhook_url');
        });
    }
}
