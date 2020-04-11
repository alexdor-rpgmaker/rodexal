<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJuryTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::connection('former_app_database')->create('jury', function(Blueprint $table)
		{
			$table->integer('id_jury', true);
			$table->integer('id_session');
			$table->string('pseudo');
			$table->integer('id_membre');
			$table->text('motivation');
			$table->tinyInteger('statut_jury')->comment('0: supprimé, 1: accepté, 2: en-attente, 3: de-cote');
			$table->tinyInteger('groupe');
			$table->boolean('is_chef_groupe')->default(0);
			$table->date('date_inscription');
			$table->date('date_validation');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::connection('former_app_database')->drop('jury');
	}
}
