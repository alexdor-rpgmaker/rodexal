<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMpTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::connection('former_app_database')->create('mp', function(Blueprint $table)
		{
			$table->integer('id_message', true);
			$table->integer('id_destinataire');
			$table->integer('id_destinateur');
			$table->integer('statut_message');
			$table->string('titre_message');
			$table->string('sous_titre_message');
			$table->text('contenu_message');
			$table->dateTime('date_publication');
			$table->dateTime('date_edition');
			$table->dateTime('date_dernier_message');
			$table->integer('nombre_edition');
			$table->integer('lu');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::connection('former_app_database')->dropIfExists('mp');
	}
}
