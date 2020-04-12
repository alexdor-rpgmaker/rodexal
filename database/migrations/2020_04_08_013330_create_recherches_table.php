<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRecherchesTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::connection('former_app_database')->create('recherches', function(Blueprint $table)
		{
			$table->integer('id_recherche', true);
			$table->string('contenu_recherche');
			$table->integer('id_membre');
			$table->integer('nb_resultats');
			$table->dateTime('date_recherche');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::connection('former_app_database')->dropIfExists('recherches');
	}
}
