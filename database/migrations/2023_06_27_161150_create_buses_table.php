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
        Schema::create('buses', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('passenger_id')->unsigned();
            $table->boolean('friday')->nullable();
            $table->boolean('saturday')->nullable();
            $table->boolean('sunday')->nullable();
            $table->decimal('amount', 10, 2)->nullable()->default(0);
            $table->text('obs')->nullable();

            $table->unsignedBigInteger('user_created_id')->nullable()->unsigned();
            $table->unsignedBigInteger('user_updated_id')->nullable()->unsigned();
            $table->unsignedBigInteger('user_deleted_id')->nullable()->unsigned();

            $table->timestamps();
            $table->softDeletes();

            $table->foreign('passenger_id')->references('id')->on('passengers');
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
        Schema::dropIfExists('buses');
    }
};
