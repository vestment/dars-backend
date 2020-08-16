<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class EditUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('ar_first_name')->nullable();
            $table->string('ar_last_name')->nullable();
            $table->string('ar_city')->nullable();
            $table->string('ar_address')->nullable();

          
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('ar_first_name');
            $table->dropColumn('ar_last_name');
            $table->dropColumn('ar_city');
            $table->dropColumn('ar_address');

        });
    }
}
