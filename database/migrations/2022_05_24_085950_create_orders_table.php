<?php

use App\Enum\OrderState;
use App\Enums\orderStatue;
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
        Schema::create('orders', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('customer_id')->unsigned();
            $table->integer('pharmacy_id')->unsigned()->nullable();
            $table->integer('driver_id')->unsigned()->nullable();
            $table->unsignedTinyInteger('state')->default(orderStatue::CREATED);
            $table->string('total_price')->nullable();
            $table->string('expected_time')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
        Schema::table('orders', function ($table) {
            $table->foreign('customer_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('pharmacy_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('driver_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
};
