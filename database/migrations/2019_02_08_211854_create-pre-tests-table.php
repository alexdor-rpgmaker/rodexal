<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePreTestsTable extends Migration
{
    public function up()
    {
        Schema::create('pre_tests', function (Blueprint $table) {
            $table->increments('id')->nullable(false);
            $table->unsignedInteger('user_id')->nullable(false);
            $table->unsignedInteger('game_id')->nullable(false);
            $table->text('questionnaire')->nullable(false);
            $table->boolean('final_thought')->nullable(false);
            $table->text('final_thought_explanation')->nullable(true);
            $table->timestamps();

            $table->unique(['user_id', 'game_id']);
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    public function down()
    {
        Schema::dropIfExists('pre_tests');
    }
}
