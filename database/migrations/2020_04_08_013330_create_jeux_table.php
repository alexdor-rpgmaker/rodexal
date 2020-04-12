<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJeuxTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::connection('former_app_database')->create('jeux', function(Blueprint $table)
		{
			$table->integer('id_jeu', true);
			$table->smallInteger('id_serie_jeu');
			$table->integer('id_session');
			$table->integer('avancement_jeu')->comment('0:demo; 1:termine');
			$table->integer('statut_jeu')->comment('0:suppr, 1:inscrit, 2:qualifie, 3:nomine, 4:vainqueur');
			$table->string('nom_jeu');
			$table->text('description_jeu');
			$table->text('commentaire_equipe');
			$table->string('genre_jeu');
			$table->string('support');
			$table->string('theme');
			$table->string('duree');
			$table->smallInteger('poids');
			$table->string('logo');
			$table->string('logo_distant');
			$table->string('site_officiel');
			$table->string('groupe');
			$table->string('lien');
			$table->string('lien_sur_mac');
			$table->string('lien_sur_site');
			$table->string('lien_sur_site_sur_mac');
			$table->boolean('is_lien_errone');
			$table->boolean('link_removed_on_author_demand');
			$table->text('informations');
			$table->dateTime('date_inscription');
			$table->integer('eligible');
			$table->integer('favori');
			$table->integer('nb_commentaires');
			$table->boolean('can_be_tested');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::connection('former_app_database')->dropIfExists('jeux');
	}
}
