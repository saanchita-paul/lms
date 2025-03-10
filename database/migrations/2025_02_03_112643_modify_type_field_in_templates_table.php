<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class ModifyTypeFieldInTemplatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('templates', function (Blueprint $table) {
            DB::statement("ALTER TABLE `templates` MODIFY `type` ENUM('email', 'text', 'appointment', 'reminder-email', 'reminder-text') NOT NULL");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('templates', function (Blueprint $table) {
            DB::statement("ALTER TABLE `templates` MODIFY `type` ENUM('email', 'text', 'appointment') NOT NULL");
        });
    }
}
