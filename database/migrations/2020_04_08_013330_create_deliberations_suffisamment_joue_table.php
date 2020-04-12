<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDeliberationsSuffisammentJoueTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::connection('former_app_database')->create('deliberations_suffisamment_joue', function(Blueprint $table)
		{
			$table->mediumInteger('id_deliberations_suffisamment_joue', true);
			$table->mediumInteger('id_membre');
			$table->mediumInteger('id_jury');
			$table->mediumInteger('id_jeu');
			$table->boolean('suffisamment_joue');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::connection('former_app_database')->dropIfExists('deliberations_suffisamment_joue');
	}
}
