<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOauthRefreshTokensTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::connection('former_app_database')->create('oauth_refresh_tokens', function(Blueprint $table)
		{
			$table->string('refresh_token', 40)->primary();
			$table->string('client_id', 80);
			$table->string('user_id', 80)->nullable();
			$table->timestamp('expires')->useCurrent();
			$table->string('scope', 4000)->nullable();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::connection('former_app_database')->drop('oauth_refresh_tokens');
	}
}
