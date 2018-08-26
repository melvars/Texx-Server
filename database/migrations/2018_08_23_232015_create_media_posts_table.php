<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMediaPostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('media_posts', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('post_id')->unsigned()->unique();
            $table->string('description');
            $table->string('media_path');

            $table->foreign('post_id')
                ->references('id')
                ->on('posts');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('media_posts');
    }
}
