<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDeliberationsNotesTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::connection('former_app_database')->create('deliberations_notes', function(Blueprint $table)
		{
			$table->integer('id_deliberations_notes', true);
			$table->integer('id_membre');
			$table->integer('id_jury');
			$table->integer('id_jeu');
			$table->integer('id_categorie');
			$table->smallInteger('note');
			$table->decimal('note_coef', 10);
			$table->smallInteger('position');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::connection('former_app_database')->dropIfExists('deliberations_notes');
	}
}
