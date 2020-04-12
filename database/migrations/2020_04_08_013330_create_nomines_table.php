<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNominesTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::connection('former_app_database')->create('nomines', function(Blueprint $table)
		{
			$table->integer('id_jeu');
			$table->integer('id_categorie');
			$table->boolean('is_vainqueur')->comment('0:non vainqueur; 1:vainqueur et/ou or; 2: argent; 3:bronze');
			$table->primary(['id_jeu','id_categorie']);
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::connection('former_app_database')->dropIfExists('nomines');
	}
}
