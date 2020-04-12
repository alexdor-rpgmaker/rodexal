<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRecapTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::connection('former_app_database')->create('recap', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->integer('id_membre');
			$table->dateTime('date_action');
			$table->string('ip', 15);
			$table->integer('id_element');
			$table->smallInteger('type_element')->comment('1:news, 2:jeu, 3:participant, 4:screen, 5:test, 6:serie_test, 7:valid_test, 8:mb, 9:coms, 10:droits, 11:deliberations, 12:pseudo, 13:inscription, 14:acces-jukebox, 15:connexion, 16:nomines');
			$table->smallInteger('type_action')->comment('1:ajout,2:modif,3:erreur,4:suppr');
			$table->string('commentaire', 100);
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::connection('former_app_database')->dropIfExists('recap');
	}
}
