<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class AddSeasonToPodcastEpisodes extends Migration
{
    public function up()
    {
        Schema::table('podcast_episodes', function (Blueprint $table) {
            $table->integer('season')->nullable(false);
        });
    }

    public function down()
    {
        Schema::table('podcast_episodes', function (Blueprint $table) {
            $table->dropColumn('season');
        });
    }
}
