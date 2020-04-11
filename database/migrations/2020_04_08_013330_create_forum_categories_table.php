<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateForumCategoriesTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::connection('former_app_database')->create('forum_categories', function(Blueprint $table)
		{
			$table->integer('id_categorie', true);
			$table->string('nom_categorie');
			$table->integer('statut_categorie');
			$table->integer('permission');
			$table->integer('position');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::connection('former_app_database')->drop('forum_categories');
	}
}
