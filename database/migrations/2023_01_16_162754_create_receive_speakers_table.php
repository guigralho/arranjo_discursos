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
        Schema::create('receive_speakers', function (Blueprint $table) {
            $table->id();

            $table->date('date');
            $table->string('speaker')->nullable();
            $table->unsignedBigInteger('schedule_id')->nullable()->unsigned();
            $table->unsignedBigInteger('speech_id')->nullable()->unsigned();

            $table->timestamps();

            $table->foreign('schedule_id')->references('id')->on('schedules');
            $table->foreign('speech_id')->references('id')->on('speeches');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('receive_speakers');
    }
};
