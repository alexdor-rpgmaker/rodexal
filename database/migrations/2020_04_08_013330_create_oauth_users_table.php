<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOauthUsersTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::connection('former_app_database')->create('oauth_users', function(Blueprint $table)
		{
			$table->string('username', 80)->default('')->primary();
			$table->string('password', 80)->nullable();
			$table->string('first_name', 80)->nullable();
			$table->string('last_name', 80)->nullable();
			$table->string('email', 80)->nullable();
			$table->boolean('email_verified')->nullable();
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
		Schema::connection('former_app_database')->drop('oauth_users');
	}
}
