<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMdpRemakeTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::connection('former_app_database')->create('mdp_remake', function(Blueprint $table)
		{
			$table->mediumInteger('id', true);
			$table->integer('nb_verif');
			$table->mediumInteger('id_mb');
			$table->string('mail', 100);
			$table->integer('timestamp');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::connection('former_app_database')->drop('mdp_remake');
	}
}
