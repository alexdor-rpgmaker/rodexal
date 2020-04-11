<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateScreenshotsDuMomentTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::connection('former_app_database')->create('screenshots_du_moment', function(Blueprint $table)
		{
			$table->smallInteger('id_screenshot', true);
			$table->string('url_screenshot');
			$table->dateTime('date');
			$table->mediumInteger('id_membre');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::connection('former_app_database')->drop('screenshots_du_moment');
	}
}
