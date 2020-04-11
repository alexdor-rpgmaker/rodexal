<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTestsAverageCharTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::connection('former_app_database')->create('tests_average_char', function(Blueprint $table)
		{
			$table->mediumInteger('id_tests_average_char', true);
			$table->mediumInteger('id_test');
			$table->mediumInteger('average_char');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::connection('former_app_database')->drop('tests_average_char');
	}
}
