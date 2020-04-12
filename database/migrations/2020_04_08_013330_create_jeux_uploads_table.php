<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJeuxUploadsTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::connection('former_app_database')->create('jeux_uploads', function(Blueprint $table)
		{
			$table->integer('id_jeu_upload', true);
			$table->string('url_jeu_upload');
			$table->integer('id_membre');
			$table->dateTime('date_upload');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::connection('former_app_database')->dropIfExists('jeux_uploads');
	}
}
