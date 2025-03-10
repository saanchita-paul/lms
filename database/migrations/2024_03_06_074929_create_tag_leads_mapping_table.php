<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTagLeadsMappingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tag_leads_mapping', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tag_id')->nullable();
            $table->foreignId('lead_id')->nullable();
            $table->timestamps();
            $table->softDeletes(); // This will add a deleted_at field for soft deletes
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tag_leads_mapping');
    }
}
