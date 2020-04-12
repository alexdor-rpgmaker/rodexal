<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class FixDefaultValues extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('former_app_database')->table('sessions', function (Blueprint $table) {
            $table->integer('statut_session')->default(1)->change();
            $table->string('nom_session')->nullable()->change();
            $table->smallInteger('etape')->default(0)->change();
            $table->dateTime('date_ajout')->nullable()->change();
            $table->date('date_lancement')->nullable()->change();
            $table->date('date_cloture_inscriptions')->nullable()->change();
            $table->date('date_annonce_nomines')->nullable()->change();
            $table->date('date_ceremonie')->nullable()->change();
            $table->text('description_session')->nullable()->change();
            $table->boolean('public_can_rank')->nullable(false)->default(false)->change();
        });

        Schema::connection('former_app_database')->table('jeux', function (Blueprint $table) {
            $table->smallInteger('id_serie_jeu')->nullable()->change();
            $table->integer('id_session')->nullable()->change();
            $table->integer('avancement_jeu')->comment('0:demo; 1:termine')->nullable()->change();
            $table->integer('statut_jeu')->comment('0:suppr, 1:inscrit, 2:qualifie, 3:nomine, 4:vainqueur')->nullable()->change();
            $table->string('nom_jeu')->nullable()->change();
            $table->text('description_jeu')->nullable()->change();
            $table->text('commentaire_equipe')->nullable()->change();
            $table->string('genre_jeu')->nullable()->change();
            $table->string('support')->nullable()->change();
            $table->string('theme')->nullable()->change();
            $table->string('duree')->nullable()->change();
            $table->smallInteger('poids')->nullable()->change();
            $table->string('logo')->nullable()->change();
            $table->string('logo_distant')->nullable()->change();
            $table->string('site_officiel')->nullable()->change();
            $table->string('groupe')->nullable()->change();
            $table->string('lien')->nullable()->change();
            $table->string('lien_sur_mac')->nullable()->change();
            $table->string('lien_sur_site')->nullable()->change();
            $table->string('lien_sur_site_sur_mac')->nullable()->change();
            $table->boolean('is_lien_errone')->nullable(false)->default(false)->change();
            $table->boolean('link_removed_on_author_demand')->nullable(false)->default(false)->change();
            $table->text('informations')->nullable()->change();
            $table->dateTime('date_inscription')->nullable()->change();
            $table->integer('eligible')->nullable()->change();
            $table->integer('favori')->nullable()->change();
            $table->integer('nb_commentaires')->nullable()->change();
            $table->boolean('can_be_tested')->nullable(false)->default(false)->change();

            $table->foreign('id_session')
                ->references('id_session')->on('sessions');
        });


        Schema::connection('former_app_database')->table('participants', function (Blueprint $table) {
            $table->integer('id_jeu')->nullable(false)->default(0)->change();
            $table->integer('id_membre')->nullable()->change();
            $table->smallInteger('statut_participant')->nullable(false)->default(1)->change();
            $table->string('nom_membre')->nullable()->change();
            $table->string('mail_membre')->nullable()->change();
            $table->string('role')->nullable()->change();
            $table->smallInteger('ordre')->nullable()->change();
            $table->boolean('peut_editer_jeu')->nullable(false)->default(true)->change();
            $table->dateTime('date_participant')->nullable()->change();

            $table->foreign('id_jeu')
                ->references('id_jeu')->on('jeux');
            $table->foreign('id_membre')
                ->references('id_membre')->on('membres');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('former_app_database')->table('jeux', function (Blueprint $table) {
            $table->dropForeign(['id_session']);
        });

        Schema::connection('former_app_database')->table('participants', function (Blueprint $table) {
            $table->dropForeign(['id_jeu']);
            $table->dropForeign(['id_membre']);
        });
    }
}
