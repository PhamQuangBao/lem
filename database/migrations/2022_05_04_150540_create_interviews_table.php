<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInterviewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('interviews', function (Blueprint $table) {
            $table->id();
            //interview cv key 1-1
            $table->integer('profile_id')->unsigned()->nullable();
            $table->foreign('profile_id')->references('id')->on('profile');
            //interviewer foreign key user n-1
            $table->integer('interviewer_id')->nullable();
            $table->foreign('interviewer_id')->references('id')->on('users');
            //overall_band foreign key 1-n
            $table->integer('overall_band_id')->unsigned()->nullable();
            $table->foreign('overall_band_id')->references('id')->on('levels');
            //skill foreign key 1-n
            $table->integer('primary_skill_id')->unsigned()->nullable();
            $table->foreign('primary_skill_id')->references('id')->on('branches');
            //level foreign key 1-n
            $table->integer('prim_level_id')->unsigned()->nullable();
            $table->foreign('prim_level_id')->references('id')->on('levels');
            //skill foreign key 1-n
            $table->integer('secondary_skill_id')->unsigned()->nullable();
            $table->foreign('secondary_skill_id')->references('id')->on('branches');
            //level foreign key 1-n
            $table->integer('second_level_id')->unsigned()->nullable();
            $table->foreign('second_level_id')->references('id')->on('levels');
            $table->text('technical_skills_note')->nullable();
            $table->string('english_level')->nullable();
            $table->string('soft_skills')->nullable();
            $table->string('overall_assessment')->nullable();
            $table->double('expected_salary')->nullable();
            $table->double('current_salary')->nullable();
            $table->date('onboard_date')->nullable();
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
        Schema::dropIfExists('interviews');
    }
}
