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
			$table->mediumInteger('id', true);
			$table->mediumInteger('id_membre');
			$table->mediumInteger('id_jeu');
			$table->tinyInteger('favori');
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
		Schema::connection('former_app_database')->drop('jeux_favoris');
	}
}
