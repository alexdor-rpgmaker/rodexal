<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGuestbookTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::connection('former_app_database')->create('guestbook', function(Blueprint $table)
		{
			$table->increments('id_signature');
			$table->mediumInteger('id_membre');
			$table->smallInteger('id_guestbook');
			$table->string('nom', 40);
			$table->string('mail', 40);
			$table->string('url', 70);
			$table->string('localisation', 40);
			$table->text('message');
			$table->dateTime('date_signature');
			$table->string('ip', 30);
			$table->string('pays', 100);
			$table->string('region', 100);
			$table->tinyInteger('statut_signature');
			$table->tinyInteger('statut_signature_old');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::connection('former_app_database')->drop('guestbook');
	}
}
