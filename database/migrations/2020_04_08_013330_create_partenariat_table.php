<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePartenariatTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::connection('former_app_database')->create('partenariat', function(Blueprint $table)
		{
			$table->mediumInteger('id_partenariat', true);
			$table->string('nom_site');
			$table->string('lien_site');
			$table->string('lien_banniere');
			$table->string('mail');
			$table->text('texte_motivation');
			$table->text('description');
			$table->text('commentaire_equipe');
			$table->dateTime('date_demande');
			$table->boolean('valide')->default(0);
			$table->tinyInteger('traite');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::connection('former_app_database')->drop('partenariat');
	}
}
