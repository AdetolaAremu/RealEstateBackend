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
            $table->uuid('id');
            $table->string('title');
            $table->string('text');
            $table->string('city');
            $table->string('address');
            $table->decimal('price');
            $table->foreignId('user_id')->references('id')->on('users');
            $table->foreignId('type')->references('id')->on('estate_types');
            $table->boolean('active')->default(1);
            $table->boolean('featured')->default(0);
            $table->boolean('main')->default(0);
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
