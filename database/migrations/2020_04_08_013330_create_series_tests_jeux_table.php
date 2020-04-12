<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSeriesTestsJeuxTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::connection('former_app_database')->create('series_tests_jeux', function(Blueprint $table)
		{
			$table->mediumInteger('id_serie');
			$table->mediumInteger('id_jeu');
			$table->primary(['id_serie','id_jeu']);
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::connection('former_app_database')->dropIfExists('series_tests_jeux');
	}
}
