<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSeriesTestsJeuxJuresTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::connection('former_app_database')->create('series_tests_jeux_jures', function(Blueprint $table)
		{
			$table->integer('id_serie');
			$table->integer('id_jeu');
			$table->integer('id_jury');
			$table->smallInteger('statut_jeu_jure')->comment('0: ???; 1: attribué a un moment ; 2: doit le faire maintenant');
			$table->primary(['id_serie','id_jeu','id_jury']);
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::connection('former_app_database')->dropIfExists('series_tests_jeux_jures');
	}
}
