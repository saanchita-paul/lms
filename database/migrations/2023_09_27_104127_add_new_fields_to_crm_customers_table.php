<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNewFieldsToCrmCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('crm_customers', function (Blueprint $table) {
            $table->tinyInteger('phone_whatsapp')->nullable()->after('name_score');
            $table->tinyInteger('phone_linkedin')->nullable()->after('phone_whatsapp');
            $table->tinyInteger('phone_amazon')->nullable()->after('phone_linkedin');
            $table->tinyInteger('phone_facebook')->nullable()->after('phone_amazon');
            $table->tinyInteger('phone_instagram')->nullable()->after('phone_facebook');
            $table->tinyInteger('phone_twitter')->nullable()->after('phone_instagram');
            $table->tinyInteger('email_whatsapp')->nullable()->after('phone_twitter');
            $table->tinyInteger('email_linkedin')->nullable()->after('email_whatsapp');
            $table->tinyInteger('email_amazon')->nullable()->after('email_linkedin');
            $table->tinyInteger('email_facebook')->nullable()->after('email_amazon');
            $table->tinyInteger('email_instagram')->nullable()->after('email_facebook');
            $table->tinyInteger('email_twitter')->nullable()->after('email_instagram');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('crm_customers', function (Blueprint $table) {
            $table->dropColumn([
                'phone_whatsapp',
                'phone_linkedin',
                'phone_amazon',
                'phone_facebook',
                'phone_instagram',
                'phone_twitter',
                'email_whatsapp',
                'email_linkedin',
                'email_amazon',
                'email_facebook',
                'email_instagram',
                'email_twitter',
            ]);
        });
    }
}
