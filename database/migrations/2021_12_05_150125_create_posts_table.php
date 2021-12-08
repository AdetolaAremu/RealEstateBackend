<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->string('text');
            $table->string('address');
            $table->decimal('price');
            $table->foreignId('user_id')->references('id')->on('users');
            $table->foreignId('type')->references('id')->on('estate_types');
            $table->foreignId('country_id')->references('id')->on('countries');
            $table->foreignId('state_id')->references('id')->on('states');
            $table->foreignId('city_id')->references('id')->on('cities');
            $table->boolean('active')->default(1);
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
        Schema::dropIfExists('posts');
    }
}
