<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDetailBookingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detail_booking', function (Blueprint $table) {
            $table->bigIncrements('id_detail_booking');
            $table->unsignedBigInteger('id_booking');
            $table->unsignedBigInteger('id_room');
            $table->date('access_date');
            $table->integer('price');
            $table->timestamps();

            $table->foreign('id_booking')->references('id_booking')->on('booking');
            $table->foreign('id_room')->references('id_room')->on('room');


        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('detail_booking');
    }
}
