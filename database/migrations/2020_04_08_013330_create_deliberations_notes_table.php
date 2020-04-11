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
			$table->mediumInteger('id_deliberations_notes', true);
			$table->mediumInteger('id_membre');
			$table->mediumInteger('id_jury');
			$table->mediumInteger('id_jeu');
			$table->mediumInteger('id_categorie');
			$table->tinyInteger('note');
			$table->decimal('note_coef', 10);
			$table->tinyInteger('position');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::connection('former_app_database')->drop('deliberations_notes');
	}
}
