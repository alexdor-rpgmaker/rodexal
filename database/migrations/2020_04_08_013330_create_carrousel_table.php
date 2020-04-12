<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCarrouselTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::connection('former_app_database')->create('carrousel', function(Blueprint $table)
		{
			$table->mediumInteger('id', true);
			$table->string('url_image');
			$table->string('alt');
			$table->string('description');
			$table->boolean('is_active');
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
		Schema::connection('former_app_database')->dropIfExists('carrousel');
	}
}
