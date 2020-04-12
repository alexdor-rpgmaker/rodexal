<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMembresTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::connection('former_app_database')->create('membres', function(Blueprint $table)
		{
			$table->integer('id_membre', true);
			$table->string('pseudo');
			$table->string('passe');
			$table->string('mail');
			$table->integer('rang');
			$table->integer('statut_membre')->comment('0: supprimé, 1: inscrit, 2: confirmé, 3: de-coté');
			$table->text('commentaire_equipe');
			$table->string('confirmation');
			$table->string('titre');
			$table->dateTime('date_inscription');
			$table->dateTime('date_visite');
			$table->date('date_naissance');
			$table->smallInteger('sexe')->comment('0:n/a,1:h,2:f');
			$table->string('ip_inscription', 20);
			$table->string('citation');
			$table->string('localisation');
			$table->string('emploi');
			$table->string('loisirs');
			$table->string('jeux_perso');
			$table->string('jeux_favoris');
			$table->string('site');
			$table->string('mail2');
			$table->string('skype', 100);
			$table->string('facebook');
			$table->string('twitter', 100);
			$table->string('avatar');
			$table->string('avatar_distant');
			$table->text('signature');
			$table->text('texte_perso');
			$table->integer('mp_non_lus');
			$table->smallInteger('design')->default(5)->comment('0:lifaen,1:walinaEtKhoryl,2:papillon,3:booskaboo,4:rpgmaker2000,5:alexre');
			$table->mediumInteger('nb_messages');
			$table->string('preferences', 20)->default('1;0;');
			$table->boolean('is_fake')->default(0);
			$table->string('discord_id', 100);
			$table->string('twitch')->nullable();
			$table->string('steam')->nullable();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::connection('former_app_database')->dropIfExists('membres');
	}
}
