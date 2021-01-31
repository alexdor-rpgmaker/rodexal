<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePodcastEpisodesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('podcast_episodes', function (Blueprint $table) {
            $table->increments('id')->nullable(false);
            $table->timestamps();

            $table->integer('number')->nullable(false);
            $table->string('title')->nullable(false);
            $table->string('slug')->nullable(false);
            $table->string('description', 500);
            $table->string('audio_url');
            $table->integer('duration_in_seconds');

            $table->integer('author_id')->unsigned();

            $table->integer('poster_id')->unsigned();
            $table->foreign('poster_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('podcast_episodes');
    }
}
