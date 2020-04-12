<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTestsFeedbacksTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::connection('former_app_database')->create('tests_feedbacks', function(Blueprint $table)
		{
			$table->integer('id_test');
			$table->integer('id_membre');
			$table->smallInteger('note');
			$table->dateTime('date');
			$table->primary(['id_test','id_membre']);
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::connection('former_app_database')->dropIfExists('tests_feedbacks');
	}
}
