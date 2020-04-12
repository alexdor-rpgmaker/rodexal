<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDeliberationsCommentairesTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::connection('former_app_database')->create('deliberations_commentaires', function(Blueprint $table)
		{
			$table->integer('id_deliberations_commentaires', true);
			$table->integer('id_membre');
			$table->integer('id_jury');
			$table->integer('id_categorie');
			$table->text('contenu_deliberation');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::connection('former_app_database')->dropIfExists('deliberations_commentaires');
	}
}
