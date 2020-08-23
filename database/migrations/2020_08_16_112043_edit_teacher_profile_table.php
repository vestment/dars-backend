<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class EditTeacherProfileTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('teacher_profiles', function (Blueprint $table) {
            $table->unsignedInteger('academy_id')->nullable();
            $table->string('ar_title')->nullable();
            $table->string('ar_description')->nullable();
            $table->foreign('academy_id')->references('id')->on('academies');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('teacher_profiles', function (Blueprint $table) {
            
            $table->dropColumn('ar_title');
            $table->dropColumn('ar_description');

        });
    }
}
