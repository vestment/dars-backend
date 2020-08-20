<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateParentStudentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(! Schema::hasTable('parent_students')) {
            Schema::create('parent_students', function (Blueprint $table) {
                $table->integer('parent_id')->unsigned();
                $table->foreign('parent_id')->references('id')->on('users')->onDelete('cascade');
                $table->integer('student_id')->unsigned();
                $table->foreign('student_id')->references('id')->on('users')->onDelete('cascade');
                $table->integer('status')->default(0);
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('parent_students');
    }
}
