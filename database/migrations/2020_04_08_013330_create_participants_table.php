<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateParticipantsTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::connection('former_app_database')->create('participants', function(Blueprint $table)
		{
			$table->integer('id_participant', true);
			$table->integer('id_jeu');
			$table->integer('id_membre');
			$table->integer('statut_participant');
			$table->string('nom_membre');
			$table->string('mail_membre');
			$table->string('role');
			$table->smallInteger('ordre')->nullable();
			$table->boolean('peut_editer_jeu')->default(1);
			$table->dateTime('date_participant')->nullable();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::connection('former_app_database')->dropIfExists('participants');
	}
}
