<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RenameCommentairesIdNewsIsCommentaireJeux extends Migration
{
    const FORMER_APP_DATABASE = 'former_app_database';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection(self::FORMER_APP_DATABASE)->table('commentaires', function (Blueprint $table) {
            $table->string('is_commentaire_jeu')->nullable(false)->change();
        });

        Schema::connection(self::FORMER_APP_DATABASE)->table('commentaires', function (Blueprint $table) {
            $table->renameColumn('id_news', 'id_parent');
            $table->renameColumn('is_commentaire_jeu', 'type_parent');
        });

        Schema::connection(self::FORMER_APP_DATABASE)->table('membres', function(Blueprint $table)
        {
            $table->integer('statut_membre')->comment('0: supprimé, 1: inscrit, 2: confirmé, 3: banni')->change();
        });
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection(self::FORMER_APP_DATABASE)->table('commentaires', function (Blueprint $table) {
            $table->renameColumn('id_parent', 'id_news');
            $table->renameColumn('type_parent', 'is_commentaire_jeu');
        });

        Schema::connection(self::FORMER_APP_DATABASE)->table('commentaires', function (Blueprint $table) {
            $table->boolean('is_commentaire_jeu')->nullable(false)->change();
        });

        Schema::connection(self::FORMER_APP_DATABASE)->table('membres', function(Blueprint $table)
        {
            $table->integer('statut_membre')->comment('0: supprimé, 1: inscrit, 2: confirmé, 3: de-coté')->change();
        });
    }
}
