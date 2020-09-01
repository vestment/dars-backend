<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Schema::create('payments', function (Blueprint $table) {
        //     $table->bigIncrements('id');
        //     $table->string('number');
        //     $table->unsignedBigInteger('user_id');
        //     $table->foreign('user_id')->references('id')->on('users');
        //     $table->unsignedBigInteger('course_id')->nullable();
        //     $table->foreign('course_id')->references('id')->on('courses');
        //     $table->unsignedBigInteger('bundle_id')->nullable();
        //     $table->foreign('bundle_id')->references('id')->on('bundles');
        //     $table->double('amount');
        //     $table->string('payment_method');
        //     $table->enum('status',['unpaid','paid','expired']);
        //     $table->string('payment_response')->nullable();
        //     $table->integer('payment_sync')->nullable();
        //     $table->timestamps();
        // });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Schema::dropIfExists('payments');
    }
}
