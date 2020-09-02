<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class EditOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->text('fawry_status')->nullable();
            $table->text('fawry_ref_no')->nullable();
            $table->text('fawry_expirationTime')->nullable();


        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('fawry_status')->nullable();
            $table->dropColumn('fawry_ref_no')->nullable();
            $table->dropColumn('fawry_expirationTime')->nullable();


        });
    }
}
