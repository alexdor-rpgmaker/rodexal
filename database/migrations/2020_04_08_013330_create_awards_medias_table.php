<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAwardsMediasTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::connection('former_app_database')->create('awards_medias', function(Blueprint $table)
		{
			$table->integer('id_media', true);
			$table->integer('id_categorie')->comment('Réunit les awards d\'une même catégorie, la même année');
			$table->integer('id_jeu');
			$table->integer('id_artiste');
			$table->string('pseudo_artiste');
			$table->smallInteger('anonymat_artiste')->comment('0: non; 1:oui');
			$table->dateTime('date_ajout_media');
			$table->smallInteger('statut_media');
			$table->smallInteger('type_media')->comment('1: image, 2: video, 3: mp3');
			$table->string('url_media');
			$table->text('description_media');
			$table->boolean('declinaison_actuelle')->comment('1: or, 2: argent, 3: bronze');
			$table->smallInteger('is_placeholder')->comment('image temporaire en attendant le vrai award');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::connection('former_app_database')->dropIfExists('awards_medias');
	}
}
