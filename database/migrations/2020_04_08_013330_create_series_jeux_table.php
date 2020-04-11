<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSeriesJeuxTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::connection('former_app_database')->create('series_jeux', function(Blueprint $table)
		{
			$table->mediumInteger('id_serie', true);
			$table->string('nom_serie');
			$table->text('description_serie');
			$table->boolean('is_serie')->comment('Une série de jeux différents');
			$table->boolean('is_meme_jeu')->comment('Un même jeu qui a évolué');
			$table->boolean('is_repost')->comment('Un même jeu qui a été re-proposé à l\'identique');
			$table->boolean('is_pourri')->comment('Trop peu de données pour en faire une série intéressante');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::connection('former_app_database')->drop('series_jeux');
	}
}
