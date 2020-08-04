<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateChaptersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('chapters', function (Blueprint $table) {
            $table->increments('id');
                $table->integer('course_id')->unsigned()->nullable();
                $table->foreign('course_id')->references('id')->on('courses')->onDelete('cascade');
                $table->string('title')->nullable();
                $table->string('slug')->nullable();
                $table->string('chapter_image')->nullable();
                $table->text('short_text')->nullable();
                $table->text('full_text')->nullable();
                $table->integer('position')->nullable()->unsigned();
                $table->tinyInteger('free_chapter')->nullable()->default(1);
                $table->tinyInteger('published')->nullable()->default(0);
                $table->timestamps();
                $table->softDeletes();
                $table->index(['deleted_at']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('chapters');
    }
}
