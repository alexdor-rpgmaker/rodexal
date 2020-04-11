<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSessionsTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::connection('former_app_database')->create('sessions', function(Blueprint $table)
		{
			$table->integer('id_session', true);
			$table->integer('statut_session');
			$table->string('nom_session');
			$table->tinyInteger('etape')->comment('0:preparation, 1:debut, 2:fin-inscription-pre-tests, 3:tests, 4:nominations, 5:vainqueurs');
			$table->dateTime('date_ajout');
			$table->date('date_lancement');
			$table->date('date_cloture_inscriptions');
			$table->date('date_annonce_nomines');
			$table->date('date_ceremonie');
			$table->text('description_session');
			$table->boolean('public_can_rank');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::connection('former_app_database')->drop('sessions');
	}
}
