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
        Schema::create('jobs', function (Blueprint $table) {
            $table->increments('id');
            //job_status foreign key 1-n
            $table->integer('job_status_id')->unsigned();
            $table->foreign('job_status_id')->references('id')->on('job_statuses');
            $table->string('key')->unique();
            $table->date('request_date');
            //skill foreign key 1-n
            $table->integer('branch_id')->unsigned()->nullable();
            $table->foreign('branch_id')->references('id')->on('branch');
            $table->text('description')->nullable();
            $table->date('close_date')->nullable();
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
        Schema::dropIfExists('jobs');
    }
};
