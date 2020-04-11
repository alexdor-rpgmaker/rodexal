<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNotesTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::connection('former_app_database')->create('notes', function(Blueprint $table)
		{
			$table->mediumInteger('id_note', true);
			$table->mediumInteger('id_test');
			$table->decimal('total', 10)->default(0.00);
			$table->decimal('col_1', 10)->nullable()->default(0.00);
			$table->decimal('col_2', 10)->nullable()->default(0.00);
			$table->decimal('col_3', 10)->nullable()->default(0.00);
			$table->decimal('col_4', 10)->nullable()->default(0.00);
			$table->decimal('col_5', 10)->nullable()->default(0.00);
			$table->decimal('col_6', 10)->nullable()->default(0.00);
			$table->decimal('col_7', 10)->nullable()->default(0.00);
			$table->decimal('col_8', 10)->nullable()->default(0.00);
			$table->decimal('col_9', 10)->nullable()->default(0.00);
			$table->decimal('col_10', 10)->nullable()->default(0.00);
			$table->decimal('col_11', 10)->nullable()->default(0.00);
			$table->decimal('col_12', 10)->nullable()->default(0.00);
			$table->decimal('col_13', 10)->nullable()->default(0.00);
			$table->decimal('col_14', 10)->nullable()->default(0.00);
			$table->decimal('col_15', 10)->nullable()->default(0.00);
			$table->decimal('col_16', 10)->nullable()->default(0.00);
			$table->decimal('col_17', 10)->nullable()->default(0.00);
			$table->decimal('col_18', 10)->nullable()->default(0.00);
			$table->decimal('col_19', 10)->nullable()->default(0.00);
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::connection('former_app_database')->drop('notes');
	}
}
