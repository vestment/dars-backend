<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class EditCoursesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->text('title_ar')->nullable();
            $table->text('description_ar')->nullable();
            $table->text('meta_title_ar')->nullable();
            $table->text('meta_description_ar')->nullable();
            $table->text('meta_keywords_ar')->nullable();







        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->dropColumn('title_ar')->nullable();
            $table->dropColumn('description_ar')->nullable();
            $table->dropColumn('meta_title_ar')->nullable();
            $table->dropColumn('meta_description_ar')->nullable();
            $table->dropColumn('meta_keywords_ar')->nullable();


        });
    }    
}

