<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSeriesTestsTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::connection('former_app_database')->create('series_tests', function(Blueprint $table)
		{
			$table->integer('id_serie', true);
			$table->integer('id_session');
			$table->string('nom_serie');
			$table->string('description_serie');
			$table->boolean('is_pre_test');
			$table->integer('statut_serie');
			$table->boolean('is_locked');
			$table->boolean('is_published');
			$table->boolean('is_published_for_jury');
			$table->integer('nb_tests_par_jeu')->default(4);
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::connection('former_app_database')->dropIfExists('series_tests');
	}
}
