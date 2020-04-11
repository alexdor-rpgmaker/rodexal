<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMailTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::connection('former_app_database')->create('mail', function(Blueprint $table)
		{
			$table->mediumInteger('id_mail', true);
			$table->tinyInteger('type_expediteur')->comment('0:invité, 1:membre, 2:equipe');
			$table->string('type_destinataire', 10)->comment('1:membre, 2:equipe, 3:membres, 4:candidats, 5:ambassadeurs, 6:jures, 7:modos, 8:admins, 9:dessinateurs');
			$table->integer('id_session_destinataire');
			$table->mediumInteger('id_membre_expediteur');
			$table->mediumInteger('id_membre_destinataire');
			$table->string('mail_expediteur');
			$table->dateTime('date');
			$table->string('sujet_mail');
			$table->text('contenu_mail');
			$table->boolean('traite');
			$table->boolean('is_mp');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::connection('former_app_database')->drop('mail');
	}
}
