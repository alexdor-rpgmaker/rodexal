<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTestsTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::connection('former_app_database')->create('tests', function(Blueprint $table)
		{
			$table->integer('id_test', true);
			$table->integer('id_serie');
			$table->integer('id_jeu');
			$table->integer('id_jury');
			$table->text('contenu');
			$table->boolean('is_apte');
			$table->smallInteger('statut_test')->comment('1: non-fait; 2: en-cours; 3: valide; 4: de-coté-par-léquipe');
			$table->dateTime('date_modification');
			$table->boolean('is_video');
			$table->string('youtube_token', 20);
			$table->integer('reviewer_id')->default(0);
			$table->dateTime('reviewed_at');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::connection('former_app_database')->dropIfExists('tests');
	}
}
