<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTextPostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('text_posts', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('post_id')->unsigned()->unique();
            $table->string('text', 8192);

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
        Schema::drop('text_posts');
    }
}
