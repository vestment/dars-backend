<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEduStageSemesterTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('edu_stage_semester', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('edu_stage_id');
            $table->foreign('edu_stage_id')->references('id')->on('edu_stages')->onDelete('cascade');
            $table->unsignedInteger('semester_id');
            $table->foreign('semester_id')->references('id')->on('semesters')->onDelete('cascade');
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
        Schema::dropIfExists('edu_stage_semester');
    }
}
