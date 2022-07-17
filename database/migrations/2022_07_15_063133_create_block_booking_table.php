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
        Schema::create('block_booking', function (Blueprint $table) {
            $table->id();
            $table->integer('booking_id');
            $table->integer('block_id');
            $table->timestamp('start')->default('2022-01-01 00:00:00');
            $table->timestamp('end')->default('2022-01-24 00:00:00');
            $table->unique(['booking_id', 'block_id', 'start', 'end'], 'unique_order');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('block_booking');
    }
};
