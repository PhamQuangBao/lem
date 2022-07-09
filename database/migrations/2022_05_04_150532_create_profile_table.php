<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('profile', function (Blueprint $table) {
            $table->increments('id');
            //job status foreign key 1-n
            $table->integer('job_id');
            $table->foreign('job_id')->references('id')->on('jobs');
            $table->date('submit_date')->nullable();
            $table->string('name')->nullable();
            $table->date('birthday')->nullable();
            $table->string('phone_number')->nullable();
            $table->string('mail')->nullable();
            $table->string('address')->nullable();
            //profile_statuses foreign key 1-n
            $table->integer('profile_status_id')->unsigned()->nullable();
            $table->foreign('profile_status_id')->references('id')->on('profile_statuses');
            //language foreign key 1-n
            $table->integer('language_id')->unsigned()->nullable();
            $table->foreign('language_id')->references('id')->on('language');
            //profile university id 1-n
            $table->integer('university_id')->unsigned()->nullable();
            $table->foreign('university_id')->references('id')->on('universities');
            $table->double('salary_offer')->nullable();
            $table->date('onboard_date')->nullable();
            $table->integer('year_of_experience')->nullable();
            $table->text('note')->nullable();
            $table->string('calendar_key')->unsigned()->nullable();
            $table->string('link')->nullable();
            $table->dateTime('time_at')->nullable();
            $table->dateTime('time_end')->nullable();
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
        Schema::dropIfExists('profile');
    }
};
