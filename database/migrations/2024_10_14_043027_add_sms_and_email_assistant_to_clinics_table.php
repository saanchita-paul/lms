<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSmsAndEmailAssistantToClinicsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('clinics', function (Blueprint $table) {
            $table->text('sms_assistant')->nullable()->after('assistant_id'); // Adding SMS assistant column
            $table->text('email_assistant')->nullable()->after('sms_assistant'); // Adding Email assistant column
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
            $table->dropColumn('sms_assistant');
            $table->dropColumn('email_assistant');
        });
    }
}
