<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVoteTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::connection('former_app_database')->create('vote', function(Blueprint $table)
		{
			$table->integer('id_vote', true);
			$table->integer('id_jeu');
			$table->integer('id_membre');
			$table->string('ip');
			$table->dateTime('date_vote');
			$table->integer('id_session');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::connection('former_app_database')->dropIfExists('vote');
	}
}
