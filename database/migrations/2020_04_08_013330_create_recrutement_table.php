<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRecrutementTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::connection('former_app_database')->create('recrutement', function(Blueprint $table)
		{
			$table->mediumInteger('id_recrutement', true);
			$table->mediumInteger('id_membre');
			$table->string('postes_interesses')->comment('1:respjure;2:communication;3:animation;4:qualite;5:;6:prez');
			$table->string('poste_main');
			$table->text('experiences');
			$table->text('motivations');
			$table->dateTime('date_recrutement');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::connection('former_app_database')->drop('recrutement');
	}
}
