<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTelechargementsTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::connection('former_app_database')->create('telechargements', function(Blueprint $table)
		{
			$table->integer('id_telechargement', true);
			$table->dateTime('date_telechargement');
			$table->string('ip', 15);
			$table->integer('id_jeu');
			$table->integer('id_membre');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::connection('former_app_database')->dropIfExists('telechargements');
	}
}
