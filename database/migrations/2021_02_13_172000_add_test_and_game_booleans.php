<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTestAndGameBooleans extends Migration
{
    const FORMER_APP_DATABASE = 'former_app_database';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection(self::FORMER_APP_DATABASE)->table('jeux', function (Blueprint $table) {
            $table->boolean('locked_link')->nullable(false)->default(false);
        });

        Schema::connection(self::FORMER_APP_DATABASE)->table('tests', function (Blueprint $table) {
            $table->boolean('is_published')->nullable(false)->default(false);
        });
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection(self::FORMER_APP_DATABASE)->table('jeux', function (Blueprint $table) {
            $table->dropColumn('locked_link');
        });

        Schema::connection(self::FORMER_APP_DATABASE)->table('tests', function (Blueprint $table) {
            $table->dropColumn('is_published');
        });
    }
}
