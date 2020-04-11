<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConnexionsTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::connection('former_app_database')->create('connexions', function(Blueprint $table)
		{
			$table->integer('id_connexion', true);
			$table->integer('id_membre');
			$table->integer('id_visiteur');
			$table->string('ip');
			$table->boolean('no_session');
			$table->dateTime('date_connexion');
			$table->dateTime('date_expiration');
			$table->text('position');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::connection('former_app_database')->drop('connexions');
	}
}
