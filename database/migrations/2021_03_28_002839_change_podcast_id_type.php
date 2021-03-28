<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangePodcastIdType extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('podcast_episodes', function (Blueprint $table) {
            $table->uuid('id')->nullable(false)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('podcast_episodes', function (Blueprint $table) {
            $table->increments('id')->nullable(false)->change();
        });
    }
}
