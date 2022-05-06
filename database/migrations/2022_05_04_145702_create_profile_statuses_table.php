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
        Schema::create('profile_statuses', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            //cv_status foreign key 1-n
            $table->integer('profile_status_group_id')->unsigned()->nullable();
            $table->foreign('profile_status_group_id')->references('id')->on('profile_status_groups');
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
        Schema::dropIfExists('profile_statuses');
    }
};
