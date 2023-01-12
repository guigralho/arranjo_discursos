<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('speaker_speech', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('speaker_id')->unsigned();
            $table->unsignedBigInteger('speech_id')->unsigned();

            $table->timestamps();

            $table->foreign('speech_id')->references('id')->on('speeches');
            $table->foreign('speaker_id')->references('id')->on('speakers');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('speaker_speech');
    }
};
