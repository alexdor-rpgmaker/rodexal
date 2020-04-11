<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCommentairesTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::connection('former_app_database')->create('commentaires', function(Blueprint $table)
		{
			$table->integer('id_commentaire', true);
			$table->integer('id_news');
			$table->integer('id_membre');
			$table->integer('statut_commentaire');
			$table->string('titre_commentaire');
			$table->text('contenu_commentaire');
			$table->string('pseudo_commentaire');
			$table->dateTime('date_publication');
			$table->dateTime('date_edition');
			$table->integer('nombre_edition');
			$table->tinyInteger('is_commentaire_jeu');
			$table->tinyInteger('is_entre_jury');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::connection('former_app_database')->drop('commentaires');
	}
}
