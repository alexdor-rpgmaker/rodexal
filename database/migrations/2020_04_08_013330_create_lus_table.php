<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLusTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::connection('former_app_database')->create('lus', function(Blueprint $table)
		{
			$table->integer('id_lu', true);
			$table->integer('id_visiteur');
			$table->integer('id_forum');
			$table->integer('id_sujet');
			$table->integer('statut_sujet');
			$table->integer('id_dernier_message_lu');
			$table->integer('id_dernier_message_poste');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::connection('former_app_database')->drop('lus');
	}
}
