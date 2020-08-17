<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class EditChapterTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('chapters', function (Blueprint $table) {
            $table->text('title_ar')->nullable();
            $table->text('short-text-ar')->nullable();
            $table->text('full-text-ar')->nullable();
            







        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('chapters', function (Blueprint $table) {

        $table->dropColumn('title_ar')->nullable();
        $table->dropColumn('short-text-ar')->nullable();
        $table->dropColumn('full-text-ar')->nullable();
    });
    }
}
