<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNewsCategoriesPivotTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::connection('former_app_database')->create('news_categories_pivot', function(Blueprint $table)
		{
			$table->mediumInteger('id_news');
			$table->mediumInteger('id_categorie');
			$table->primary(['id_news','id_categorie']);
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::connection('former_app_database')->drop('news_categories_pivot');
	}
}
