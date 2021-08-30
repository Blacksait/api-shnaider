<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSpeakers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('speakers', function (Blueprint $table) {
            $table->unsignedBigInteger('speaker_id')->primary();
            $table->bigInteger('questionid')->unsigned();
            $table->string('speaker_fname')->nullable();
            $table->string('speaker_mname')->nullable();
            $table->string('speaker_lname')->nullable();
            $table->string('speaker_image')->nullable();
            $table->string('speaker_titles')->nullable();
            $table->string('speaker_companies')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('speakers');
    }
}
