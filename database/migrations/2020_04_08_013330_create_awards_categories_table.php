<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAwardsCategoriesTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::connection('former_app_database')->create('awards_categories', function(Blueprint $table)
		{
			$table->integer('id_categorie', true);
			$table->integer('id_serie_categorie');
			$table->string('nom_categorie');
			$table->text('description_categorie');
			$table->boolean('niveau_categorie')->comment('1: meilleurjeu, 2: general, 3: specialisé, 4: coupdecoeur');
			$table->integer('id_session');
			$table->dateTime('date_ajout_categorie');
			$table->smallInteger('statut_categorie');
			$table->boolean('is_declinaison');
			$table->boolean('is_after_tests');
			$table->smallInteger('ordre');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::connection('former_app_database')->dropIfExists('awards_categories');
	}
}
