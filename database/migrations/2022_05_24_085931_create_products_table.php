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
        Schema::create('products', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('pharmacy_id')->unsigned();
            $table->string('name');
            $table->string('company');
            $table->string('image');
            $table->string('price');
            $table->string('type');
            $table->boolean('available');
            $table->string('amount')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
        Schema::table('products', function ($table) {
            $table->foreign('pharmacy_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
};
