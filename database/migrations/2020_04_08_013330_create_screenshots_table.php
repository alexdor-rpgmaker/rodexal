<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateScreenshotsTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::connection('former_app_database')->create('screenshots', function(Blueprint $table)
		{
			$table->integer('id_screenshot', true);
			$table->integer('id_jeu');
			$table->string('nom_screenshot');
			$table->text('distant');
			$table->text('local');
			$table->integer('statut_screenshot');
			$table->tinyInteger('ordre');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::connection('former_app_database')->drop('screenshots');
	}
}
