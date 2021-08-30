<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UsersSessionlist extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_sessionlist', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->references('attendee_id')->on('users');
            $table->foreignId('session_id')->references('sessionid')->on('session_list');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_sessionlist');
    }
}
