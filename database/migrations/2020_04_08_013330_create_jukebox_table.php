<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJukeboxTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::connection('former_app_database')->create('jukebox', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->string('titre');
			$table->integer('id_posteur');
			$table->dateTime('date_publication');
			$table->text('com');
			$table->integer('id_jeu_origine');
			$table->string('url_fichier');
			$table->boolean('statut_zik')->default(1);
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::connection('former_app_database')->dropIfExists('jukebox');
	}
}
