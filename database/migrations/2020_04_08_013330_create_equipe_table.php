<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEquipeTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::connection('former_app_database')->create('equipe', function(Blueprint $table)
		{
			$table->mediumInteger('id_equipe', true);
			$table->mediumInteger('id_membre');
			$table->string('pseudo');
			$table->smallInteger('id_session');
			$table->string('role');
			$table->smallInteger('type_role')->comment('1:president;2:chef jure;3:respo site web;4:communication-externe;5:ambassadeur;6:illustrateur;7:aide;8:meilleur-jure');
			$table->smallInteger('ordre');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::connection('former_app_database')->dropIfExists('equipe');
	}
}
