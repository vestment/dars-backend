<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnWishlistToStudenCourses extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('course_student', function (Blueprint $table) {
           
                $table->boolean('wishlist')->nullable()->default(0);
            });
       
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('course_student', function (Blueprint $table) {
            $table->dropColumn('wishlist');
        });
    }
}
