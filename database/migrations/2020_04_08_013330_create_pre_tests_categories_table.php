<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePreTestsCategoriesTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::connection('former_app_database')->create('pre_tests_categories', function(Blueprint $table)
		{
			$table->integer('id_serie');
			$table->integer('id_jeu');
			$table->integer('id_jury');
			$table->integer('id_categorie');
			$table->smallInteger('statut_ptc');
			$table->primary(['id_serie','id_jeu','id_jury','id_categorie'], 'pre_tests_attributions_primary');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::connection('former_app_database')->dropIfExists('pre_tests_categories');
	}
}
