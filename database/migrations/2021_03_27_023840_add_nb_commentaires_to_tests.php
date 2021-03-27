<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AddNbCommentairesToTests extends Migration
{
    const FORMER_APP_DATABASE = 'former_app_database';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection(self::FORMER_APP_DATABASE)->table('tests', function (Blueprint $table) {
            $table->integer('nb_commentaires')->nullable(false)->default(0);
        });

        DB::connection(self::FORMER_APP_DATABASE)->update(
            'UPDATE jeux SET nb_commentaires = 0 WHERE nb_commentaires IS NULL'
        );

        Schema::connection(self::FORMER_APP_DATABASE)->table('jeux', function (Blueprint $table) {
            $table->integer('nb_commentaires')->nullable(false)->default(0)->change();
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
            $table->integer('nb_commentaires')->nullable()->change();
        });

        Schema::connection(self::FORMER_APP_DATABASE)->table('tests', function (Blueprint $table) {
            $table->dropColumn(['nb_commentaires']);
        });
    }
}
