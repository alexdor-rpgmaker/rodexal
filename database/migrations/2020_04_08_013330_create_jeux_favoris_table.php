<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJeuxFavorisTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::connection('former_app_database')->create('jeux_favoris', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->integer('id_membre');
			$table->integer('id_jeu');
			$table->smallInteger('favori');
			$table->dateTime('date_modification');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::connection('former_app_database')->dropIfExists('jeux_favoris');
	}
}
