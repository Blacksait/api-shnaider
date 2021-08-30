<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSessionList extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('session_list', function (Blueprint $table) {
            $table->unsignedbigInteger('session_id');
//            $table->foreignId('speaker_id')->references('speaker_id')->on('speakers');
            $table->string('speaker_ids');
            $table->string('sort');
            $table->string('name',1024);
            $table->date('sessiondate')->nullable();
            $table->time('starttime')->nullable();
            $table->time('endtime')->nullable();
//            $table->unsignedbigInteger('location_id')->nullable();
            $table->foreignId('location_id')->references('location_id')->on('locations');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('session_list');
    }
}
