<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class EditTestTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumns('tests', ['available','no_questions','timer'])) {
            Schema::table('tests', function (Blueprint $table) {
                $table->integer('available')->nullable()->default(1);
                $table->tinyInteger('no_questions')->nullable()->default(0);
                $table->text('timer')->nullable();
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
        Schema::table('tests', function (Blueprint $table) {
            
            $table->dropColumn('timer')->nullable();
           
           
         
        });
    }
}
