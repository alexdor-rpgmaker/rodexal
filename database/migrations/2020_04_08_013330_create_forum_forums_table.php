<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateForumForumsTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::connection('former_app_database')->create('forum_forums', function(Blueprint $table)
		{
			$table->integer('id_forum', true);
			$table->integer('id_categorie');
			$table->integer('statut_forum');
			$table->integer('position_forum');
			$table->string('titre_forum');
			$table->string('sous_titre_forum');
			$table->integer('permission_forum');
			$table->integer('nombre_sujets');
			$table->integer('nombre_messages');
			$table->integer('id_dernier_message_forum');
			$table->integer('parent_forum_id');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::connection('former_app_database')->dropIfExists('forum_forums');
	}
}
