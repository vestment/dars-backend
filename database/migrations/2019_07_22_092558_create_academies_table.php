<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAcademiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('academies', function (Blueprint $table) {
          
            $table->increments('id');
            $table->unsignedInteger('user_id');
            $table->text('facebook_link')->nullable();
            $table->text('twitter_link')->nullable();
            $table->text('linkedin_link')->nullable();
            $table->string('payment_method')->comment('paypal,bank');
            $table->text('payment_details');
            $table->string('percentage');
            $table->text('logo');
            $table->json('gallary')->nullable();
            $table->text('adress');
            $table->text('description');
            $table->softDeletes();
            $table->foreign('user_id')->references('id')->on('users');
            $table->timestamps();

           
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('academies');
    }
}
