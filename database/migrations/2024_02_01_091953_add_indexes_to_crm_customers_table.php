<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIndexesToCrmCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('crm_customers', function (Blueprint $table) {
            $table->index('first_name', 'idx_first_name');
            $table->index('last_name', 'idx_last_name');
            $table->index('email', 'idx_email');
            $table->index('phone', 'idx_phone');
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
            $table->dropIndex('idx_first_name');
            $table->dropIndex('idx_last_name');
            $table->dropIndex('idx_email');
            $table->dropIndex('idx_phone');
        });
    }
}
