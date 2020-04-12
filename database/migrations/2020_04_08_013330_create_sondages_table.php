<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSondagesTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::connection('former_app_database')->create('sondages', function(Blueprint $table)
		{
			$table->integer('id_sondage', true);
			$table->string('question');
			$table->smallInteger('nb_reponses');
			$table->string('reponse1');
			$table->string('reponse2');
			$table->string('reponse3');
			$table->string('reponse4');
			$table->string('reponse5');
			$table->smallInteger('votes1')->default(0);
			$table->smallInteger('votes2')->default(0);
			$table->smallInteger('votes3')->default(0);
			$table->smallInteger('votes4')->default(0);
			$table->smallInteger('votes5')->default(0);
			$table->smallInteger('nb_votes')->default(0);
			$table->smallInteger('statut_sondage')->default(1);
			$table->dateTime('date_sondage');
			$table->boolean('multiple')->default(0);
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::connection('former_app_database')->dropIfExists('sondages');
	}
}
