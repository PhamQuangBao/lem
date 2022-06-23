<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProfileForEmailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('profile_for_emails', function (Blueprint $table) {
            $table->increments('id');
            //job_status foreign key 1-1
            $table->integer('profile_id')->unsigned();
            // $table->foreign('profile_id')->references('id')->on('profile');
            $table->string('email_id')->nullable();
            $table->string('label')->nullable();
            $table->string('form_name')->nullable();
            $table->string('form_email')->nullable();
            $table->dateTime('time_send')->nullable();
            $table->string('subject')->nullable();
            $table->integer('number_attachment')->nullable();
            $table->string('auth_email')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('profile_for_emails');
    }
}
