<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNewsTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::connection('former_app_database')->create('news', function(Blueprint $table)
		{
			$table->integer('id_news', true);
			$table->integer('id_membre');
			$table->string('nom_news');
			$table->text('contenu_news');
			$table->integer('statut_news');
			$table->dateTime('date_creation_news');
			$table->dateTime('date_validation_news');
			$table->integer('nb_commentaires');
			$table->smallInteger('origine')->default(4);
			$table->boolean('is_blog');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::connection('former_app_database')->dropIfExists('news');
	}
}
