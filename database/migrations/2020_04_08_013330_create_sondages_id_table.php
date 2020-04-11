<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSondagesIdTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::connection('former_app_database')->create('sondages_id', function(Blueprint $table)
		{
			$table->mediumInteger('id_sondages_id', true);
			$table->mediumInteger('id_membre');
			$table->mediumInteger('id_sondage');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::connection('former_app_database')->drop('sondages_id');
	}
}
