<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVideosTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::connection('former_app_database')->create('videos', function(Blueprint $table)
		{
			$table->integer('id_video', true);
			$table->string('nom_video');
			$table->string('url_video');
			$table->integer('id_jeu');
			$table->integer('id_membre');
			$table->dateTime('date_publication');
			$table->smallInteger('statut_video');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::connection('former_app_database')->dropIfExists('videos');
	}
}
