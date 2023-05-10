<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBookingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('booking', function (Blueprint $table) {
            $table->bigIncrements('id_booking');
            $table->integer('booking_number');
            $table->string('name_customer');
            $table->string('email_customer');
            $table->timestamp('booking_date');
            $table->date('check_in_date');
            $table->date('check_out_date');
            $table->string('guest_name', 100);
            $table->integer('room_quantity');
            $table->unsignedBigInteger('id_type_room');
            $table->enum('status_booking', ['new', 'check_in', 'check_out']);
            $table->unsignedBigInteger('id_user');
            $table->timestamps();

            $table->foreign('id_type_room')->references('id_type_room')->on('type_room');
            $table->foreign('id_user')->references('id_user')->on('users');

        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('booking');
    }
}
