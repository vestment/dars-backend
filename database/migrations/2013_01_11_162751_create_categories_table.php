<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // error will occur if this table was not pre-added into the database first
        // Schema::dropIfExists('categories');
        if (!Schema::hasTable('categories')) {
            Schema::create('categories', function (Blueprint $table) {
                $table->increments('id')->unsigned();
                $table->string('name');
                $table->string('slug')->nullable();
                $table->text('icon')->nullable();
                $table->integer('status')->default(1)->comment('0 - disabled, 1 - enabled');
                $table->timestamps();
                $table->softDeletes();
                $table->string('ar_name')->nullable();

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
        // Schema::dropIfExists('categories');
    }
}
