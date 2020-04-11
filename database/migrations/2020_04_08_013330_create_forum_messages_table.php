<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateForumMessagesTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::connection('former_app_database')->create('forum_messages', function(Blueprint $table)
		{
			$table->integer('id_message', true);
			$table->integer('id_sujet');
			$table->integer('id_forum');
			$table->integer('id_membre');
			$table->integer('type_message');
			$table->integer('statut_message');
			$table->string('titre_message');
			$table->string('sous_titre_message');
			$table->text('contenu_message');
			$table->dateTime('date_publication');
			$table->dateTime('date_edition');
			$table->dateTime('date_dernier_message');
			$table->integer('nombre_edition');
			$table->integer('nombre_reponses');
			$table->integer('nombre_visites');
			$table->integer('dernier_reponse');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::connection('former_app_database')->drop('forum_messages');
	}
}
