<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('passengers', function (Blueprint $table) {
            $table->id();

            $table->string('name');
            $table->string('doc')->nullable();
            $table->string('phone')->nullable();

            $table->unsignedBigInteger('user_created_id')->nullable()->unsigned();
            $table->unsignedBigInteger('user_updated_id')->nullable()->unsigned();
            $table->unsignedBigInteger('user_deleted_id')->nullable()->unsigned();

            $table->timestamps();
            $table->softDeletes();

            $table->foreign('user_created_id')->references('id')->on('users');
            $table->foreign('user_updated_id')->references('id')->on('users');
            $table->foreign('user_deleted_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('passengers');
    }
};
