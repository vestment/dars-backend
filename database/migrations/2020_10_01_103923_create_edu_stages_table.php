<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEduStagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('edu_stages', function (Blueprint $table) {
            $table->increments('id');
            $table->string('ar_name');
            $table->string('en_name');
            $table->unsignedInteger('edu_system_id');
            $table->foreign('edu_system_id')->references('id')->on('edu_systems')->onDelete('cascade');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('edu_stages');
    }
}
