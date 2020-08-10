<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnTitleToTeacherProfilesTable extends Migration
{
    /**
     * Run the migryyyations.
     *
     * @return void
     */
    public function up()
    
    {
        Schema::table('teacher_profiles', function (Blueprint $table) {
            
            $table->text('title');
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
            
            $table->dropColumn('title');

        });
    }
}
