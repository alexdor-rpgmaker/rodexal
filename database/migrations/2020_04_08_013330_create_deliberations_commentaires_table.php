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
			$table->mediumInteger('id_deliberations_commentaires', true);
			$table->mediumInteger('id_membre');
			$table->mediumInteger('id_jury');
			$table->mediumInteger('id_categorie');
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
