<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTemplatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('templates', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('clinic_id'); // Create clinic_id column
            $table->unsignedBigInteger('user_id'); // Create user_id column
            $table->text('template_name');
            $table->text('subject')->nullable();
            $table->longText('body');
            $table->enum('type', ['email', 'text']);
            $table->softDeletes();
            $table->timestamps();
        
            // Foreign key constraints
            $table->foreign('clinic_id')->references('id')->on('clinics')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('templates');
    }
}
