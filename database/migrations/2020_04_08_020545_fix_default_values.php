<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class FixDefaultValues extends Migration
{
    const FORMER_APP_DATABASE = 'former_app_database';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // TODO Remove

//        $tables = [
//            'users',
//            'products',
//        ];
//        foreach ($tables as $table) {
//            DB::statement('ALTER TABLE ' . $table . ' ENGINE = InnoDB');
//        }

        // TODO: Delete the OAuth2 tables in former database when the user login is done directly on the new app
        // Schema::connection(self::FORMER_APP_DATABASE)->dropIfExists('oauth_authorization_codes');
        // Schema::connection(self::FORMER_APP_DATABASE)->dropIfExists('oauth_clients');
        // Schema::connection(self::FORMER_APP_DATABASE)->dropIfExists('oauth_refresh_tokens');
        // Schema::connection(self::FORMER_APP_DATABASE)->dropIfExists('oauth_scopes');
        // Schema::connection(self::FORMER_APP_DATABASE)->dropIfExists('oauth_users');
        // Schema::connection(self::FORMER_APP_DATABASE)->dropIfExists('oauth_jwt');
        // Schema::connection(self::FORMER_APP_DATABASE)->dropIfExists('oauth_access_tokens');

        // Sessions

        DB::connection(self::FORMER_APP_DATABASE)->update(
            'UPDATE sessions SET date_ajout = "1901-03-21 23:59:11" WHERE date_ajout = 0'
        );
        DB::connection(self::FORMER_APP_DATABASE)->update(
            'UPDATE sessions SET date_ceremonie = "1901-03-21" WHERE date_ceremonie = 0'
        );
        DB::connection(self::FORMER_APP_DATABASE)->update(
            'UPDATE sessions SET date_cloture_inscriptions = "1901-03-21" WHERE date_cloture_inscriptions = 0'
        );
        DB::connection(self::FORMER_APP_DATABASE)->update(
            'UPDATE sessions SET date_annonce_nomines = "1901-03-21" WHERE date_annonce_nomines = 0'
        );
        DB::connection(self::FORMER_APP_DATABASE)->update(
            'UPDATE sessions SET date_lancement = "1901-03-21" WHERE date_lancement = 0'
        );

        DB::connection(self::FORMER_APP_DATABASE)->statement(
            'ALTER TABLE sessions ENGINE = InnoDB'
        );

        Schema::connection(self::FORMER_APP_DATABASE)->table('sessions', function (Blueprint $table) {
            $table->smallInteger('statut_session')->default(1)->change();
            $table->string('nom_session')->nullable()->change();
            $table->smallInteger('etape')->default(0)->comment('0: préparation; 1: début; 2: fin-inscription-pré-tests; 3: tests; 4: nominations; 5: vainqueurs')->change();
            $table->dateTime('date_ajout')->nullable()->change();
            $table->date('date_lancement')->nullable()->change();
            $table->date('date_cloture_inscriptions')->nullable()->change();
            $table->date('date_annonce_nomines')->nullable()->change();
            $table->date('date_ceremonie')->nullable()->change();
            $table->text('description_session')->nullable()->change();
            $table->text('team_top_text')->nullable()->change();
            $table->text('team_bottom_text')->nullable()->change();
            $table->boolean('public_can_rank')->nullable(false)->default(false)->change();
        });

        DB::connection(self::FORMER_APP_DATABASE)->update(
            'UPDATE sessions SET date_ajout = NULL WHERE date_ajout = "1901-03-21 23:59:11"'
        );
        DB::connection(self::FORMER_APP_DATABASE)->update(
            'UPDATE sessions SET date_ceremonie = NULL WHERE date_ceremonie = "1901-03-21"'
        );
        DB::connection(self::FORMER_APP_DATABASE)->update(
            'UPDATE sessions SET date_cloture_inscriptions = NULL WHERE date_cloture_inscriptions = "1901-03-21"'
        );
        DB::connection(self::FORMER_APP_DATABASE)->update(
            'UPDATE sessions SET date_annonce_nomines = NULL WHERE date_annonce_nomines = "1901-03-21"'
        );
        DB::connection(self::FORMER_APP_DATABASE)->update(
            'UPDATE sessions SET date_lancement = NULL WHERE date_lancement = "1901-03-21"'
        );

        // Membres

        DB::connection(self::FORMER_APP_DATABASE)->update(
            'UPDATE membres SET date_naissance = "1901-03-21" WHERE date_naissance = 0'
        );
        DB::connection(self::FORMER_APP_DATABASE)->update(
            'UPDATE membres SET date_visite = "1901-03-21 23:59:11" WHERE date_visite = 0'
        );
        DB::connection(self::FORMER_APP_DATABASE)->update(
            'UPDATE membres SET date_inscription = "1901-03-21 23:59:11" WHERE date_inscription = 0'
        );

        DB::connection(self::FORMER_APP_DATABASE)->statement(
            'ALTER TABLE membres ENGINE = InnoDB'
        );

        Schema::connection(self::FORMER_APP_DATABASE)->table('membres', function (Blueprint $table) {
            $table->integer('id_membre', true)->nullable(false)->change();

            $table->text('commentaire_equipe')->nullable()->change();
            $table->string('confirmation')->nullable()->change();
            $table->string('titre')->nullable()->change();
            $table->dateTime('date_inscription')->nullable()->change();
            $table->dateTime('date_visite')->nullable()->change();
            $table->date('date_naissance')->nullable()->change();
            $table->smallInteger('sexe')->nullable(false)->default(0)->comment('0: n/a; 1: h; 2: f')->change();
            $table->string('ip_inscription', 20)->nullable()->change();
            $table->string('citation')->nullable()->change();
            $table->string('localisation')->nullable()->change();
            $table->string('emploi')->nullable()->change();
            $table->string('loisirs')->nullable()->change();
            $table->string('jeux_perso')->nullable()->change();
            $table->string('jeux_favoris')->nullable()->change();
            $table->string('site')->nullable()->change();
            $table->string('mail2')->nullable()->change();
            $table->string('skype', 100)->nullable()->change();
            $table->string('facebook')->nullable()->change();
            $table->string('twitter', 100)->nullable()->change();
            $table->string('avatar')->nullable()->change();
            $table->string('avatar_distant')->nullable()->change();
            $table->text('signature')->nullable()->change();
            $table->text('texte_perso')->nullable()->change();
            $table->integer('mp_non_lus')->nullable(false)->default(0)->change();
            $table->smallInteger('design')->nullable(false)->default(5)->comment('0: Lifaen; 1: Walina & Khoryl; 2: Papillon; 3: Booskaboo; 4: RPG Maker 2000; 5: AlexRE')->change();
            $table->integer('nb_messages')->nullable(false)->default(0)->change();
            $table->string('preferences', 20)->default('1;0;')->change();
            $table->boolean('is_fake')->nullable(false)->default(false)->change();
            $table->string('discord_id', 100)->nullable()->change();
            $table->string('twitch')->nullable()->change();
            $table->string('steam')->nullable()->change();
        });

        DB::connection(self::FORMER_APP_DATABASE)->update(
            'UPDATE membres SET date_naissance = NULL WHERE date_naissance = "1901-03-21"'
        );
        DB::connection(self::FORMER_APP_DATABASE)->update(
            'UPDATE membres SET date_visite = NULL WHERE date_visite = "1901-03-21 23:59:11"'
        );
        DB::connection(self::FORMER_APP_DATABASE)->update(
            'UPDATE membres SET date_inscription = NULL WHERE date_inscription = "1901-03-21 23:59:11"'
        );

        // Séries jeux

        Schema::connection(self::FORMER_APP_DATABASE)->table('series_jeux', function (Blueprint $table) {
            $table->integer('id_serie', true)->index()->change();

            $table->boolean('is_serie')->nullable(false)->default(false)->comment('Une série de jeux différents')->change();
            $table->boolean('is_meme_jeu')->nullable(false)->default(false)->comment('Un même jeu qui a évolué')->change();
            $table->boolean('is_repost')->nullable(false)->default(false)->comment('Un même jeu qui a été re-proposé à l\'identique')->change();
            $table->boolean('trop_peu_donnees')->nullable(false)->default(false)->comment('Trop peu de données pour en faire une série intéressante')->change();
        });

        // Jeux

        DB::connection(self::FORMER_APP_DATABASE)->update(
            'UPDATE jeux SET date_inscription = "1901-03-21 23:59:11" WHERE date_inscription = 0'
        );

        DB::connection(self::FORMER_APP_DATABASE)->statement(
            'ALTER TABLE jeux ENGINE = InnoDB'
        );

        Schema::connection(self::FORMER_APP_DATABASE)->table('jeux', function (Blueprint $table) {
            $table->integer('id_jeu', true)->index()->change();

            $table->integer('id_serie_jeu')->nullable()->change();
            $table->integer('id_session')->nullable()->change();
            $table->integer('avancement_jeu')->comment('0: démo; 1: terminé')->nullable()->change();
            $table->smallInteger('statut_jeu')->comment('0: supprimé; 1: inscrit; 2: qualifié; 3: nominé; 4: vainqueur')->nullable()->change();
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
            $table->string('nouveau_lien_apres_session')->nullable()->change();
            $table->text('informations')->nullable()->change();
            $table->dateTime('date_inscription')->nullable()->change();
            $table->integer('eligible')->nullable()->change();
            $table->integer('favori')->nullable()->change();
            $table->integer('nb_commentaires')->nullable()->change();
            $table->boolean('can_be_tested')->nullable(false)->default(false)->change();
        });

        DB::connection(self::FORMER_APP_DATABASE)->delete(
            'UPDATE jeux SET id_serie_jeu = NULL WHERE id_serie_jeu NOT IN (SELECT id_serie FROM series_jeux)'
        );

        Schema::connection(self::FORMER_APP_DATABASE)->table('jeux', function (Blueprint $table) {
            $table->foreign('id_session')
                ->references('id_session')->on('sessions');
            $table->foreign('id_serie_jeu')
                ->references('id_serie')->on('series_jeux');
        });

        DB::connection(self::FORMER_APP_DATABASE)->update(
            'UPDATE jeux SET date_inscription = NULL WHERE date_inscription = "1901-03-21 23:59:11"'
        );

        // Participants & other tables

        Schema::connection(self::FORMER_APP_DATABASE)->table('participants', function (Blueprint $table) {
            $table->integer('id_jeu')->nullable(false)->change();
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

        Schema::connection(self::FORMER_APP_DATABASE)->table('screenshots', function (Blueprint $table) {
            $table->integer('id_jeu')->nullable(false)->change();
            $table->string('nom_screenshot')->nullable()->change();
            $table->text('distant')->nullable()->change();
            $table->text('local')->nullable()->change();
            $table->smallInteger('statut_screenshot')->nullable(false)->default(1)->change();
            $table->smallInteger('ordre')->nullable()->change();

            $table->foreign('id_jeu')
                ->references('id_jeu')->on('jeux');
        });

        Schema::connection(self::FORMER_APP_DATABASE)->table('nomines', function (Blueprint $table) {
            $table->smallInteger('is_vainqueur')->comment('0: non vainqueur; 1: vainqueur et/ou or; 2: argent; 3: bronze')->nullable(false)->change();

            $table->foreign('id_jeu')
                ->references('id_jeu')->on('jeux');
            $table->foreign('id_categorie')
                ->references('id_categorie')->on('awards_categories');
        });

        // Jeux favoris

        DB::connection(self::FORMER_APP_DATABASE)->update(
            'UPDATE jeux_favoris SET date_modification = "1901-03-21 23:59:11" WHERE date_modification = 0'
        );

        DB::connection(self::FORMER_APP_DATABASE)->delete(
            'DELETE FROM jeux_favoris WHERE id_membre NOT IN (SELECT id_membre FROM membres)'
        );

        DB::connection(self::FORMER_APP_DATABASE)->statement(
            'ALTER TABLE jeux_favoris ENGINE = InnoDB'
        );

        Schema::connection(self::FORMER_APP_DATABASE)->table('jeux_favoris', function (Blueprint $table) {
            $table->integer('id_jeu')->nullable(false)->change();
            $table->integer('id_membre')->nullable(false)->change();
            $table->boolean('favori')->nullable(false)->default(true)->change();
            $table->dateTime('date_modification')->nullable()->change();

            $table->foreign('id_membre')
                ->references('id_membre')->on('membres');
            $table->foreign('id_jeu')
                ->references('id_jeu')->on('jeux');
        });

        DB::connection(self::FORMER_APP_DATABASE)->update(
            'UPDATE jeux_favoris SET date_modification = NULL WHERE date_modification = "1901-03-21 23:59:11"'
        );

        // Jury

        DB::connection(self::FORMER_APP_DATABASE)->update(
            'UPDATE jury SET date_inscription = "1901-03-21" WHERE date_inscription = 0'
        );
        DB::connection(self::FORMER_APP_DATABASE)->update(
            'UPDATE jury SET date_validation = "1901-03-21" WHERE date_validation = 0'
        );

        DB::connection(self::FORMER_APP_DATABASE)->statement(
            'ALTER TABLE jury ENGINE = InnoDB'
        );

        Schema::connection(self::FORMER_APP_DATABASE)->table('jury', function (Blueprint $table) {
            $table->integer('id_membre')->nullable()->change();

            $table->string('pseudo')->nullable()->change();
            $table->text('motivation')->nullable()->change();
            $table->smallInteger('statut_jury')->comment('0: supprimé; 1: accepté; 2: en-attente; 3: de coté')->default(2)->change();
            $table->smallInteger('groupe')->nullable()->change();
            $table->boolean('is_chef_groupe')->nullable(false)->default(false)->change();
            $table->date('date_inscription')->nullable()->change();
            $table->date('date_validation')->nullable()->change();
        });

        DB::connection(self::FORMER_APP_DATABASE)->update(
            'UPDATE jury SET id_membre = NULL WHERE id_membre = 0'
        );

        Schema::connection(self::FORMER_APP_DATABASE)->table('jury', function (Blueprint $table) {
            $table->foreign('id_session')
                ->references('id_session')->on('sessions');
            $table->foreign('id_membre')
                ->references('id_membre')->on('membres');
        });

        DB::connection(self::FORMER_APP_DATABASE)->update(
            'UPDATE jury SET date_inscription = NULL WHERE date_inscription = "1901-03-21"'
        );
        DB::connection(self::FORMER_APP_DATABASE)->update(
            'UPDATE jury SET date_validation = NULL WHERE date_validation = "1901-03-21"'
        );

        // series_tests

        DB::connection(self::FORMER_APP_DATABASE)->statement(
            'ALTER TABLE series_tests ENGINE = InnoDB'
        );

        Schema::connection(self::FORMER_APP_DATABASE)->table('series_tests', function (Blueprint $table) {
            $table->integer('id_serie', true)->index()->change();

            $table->string('nom_serie')->nullable(false)->change();
            $table->string('description_serie')->nullable()->change();
            $table->boolean('is_pre_test')->nullable(false)->default(false)->change();
            $table->smallInteger('statut_serie')->default(1)->nullable(false)->change();
            $table->boolean('is_locked')->nullable(false)->default(false)->change();
            $table->boolean('is_published')->nullable(false)->default(false)->change();
            $table->boolean('is_published_for_jury')->nullable(false)->default(false)->change();
            $table->smallInteger('nb_tests_par_jeu')->nullable(false)->default(4)->change();

            $table->foreign('id_session')
                ->references('id_session')->on('sessions');
        });

        Schema::connection(self::FORMER_APP_DATABASE)->table('series_tests_jeux', function (Blueprint $table) {
            $table->integer('id_serie')->nullable(false)->change();
            $table->integer('id_jeu')->nullable(false)->change();

            $table->foreign('id_serie')
                ->references('id_serie')->on('series_tests');
            $table->foreign('id_jeu')
                ->references('id_jeu')->on('jeux');
        });

        DB::connection(self::FORMER_APP_DATABASE)->delete(
            'DELETE FROM series_tests_jeux_jures WHERE id_jury = 0 OR id_serie = 0'
        );

        Schema::connection(self::FORMER_APP_DATABASE)->table('series_tests_jeux_jures', function (Blueprint $table) {
            $table->integer('id_serie')->nullable(false)->change();
            $table->integer('id_jeu')->nullable(false)->change();
            $table->integer('id_jury')->nullable(false)->change();

            $table->smallInteger('statut_jeu_jure')->default(2)->nullable(false)->comment('0: ???; 1: attribué à un moment; 2: doit le faire maintenant')->change();

            $table->foreign('id_serie')
                ->references('id_serie')->on('series_tests');
            $table->foreign('id_jeu')
                ->references('id_jeu')->on('jeux');
            $table->foreign('id_jury')
                ->references('id_jury')->on('jury');
        });

        // tests

        DB::connection(self::FORMER_APP_DATABASE)->update(
            'UPDATE tests SET date_modification = "1901-03-21 23:59:11" WHERE date_modification = 0'
        );
        DB::connection(self::FORMER_APP_DATABASE)->update(
            'UPDATE tests SET reviewed_at = "1901-03-21 23:59:11" WHERE reviewed_at = 0'
        );

        DB::connection(self::FORMER_APP_DATABASE)->statement(
            'ALTER TABLE tests ENGINE = InnoDB'
        );

        Schema::connection(self::FORMER_APP_DATABASE)->table('tests', function (Blueprint $table) {
            $table->integer('id_serie')->change();
            $table->integer('id_jeu')->change();

            $table->text('contenu')->nullable()->change();
            $table->boolean('is_apte')->nullable()->change();
            $table->smallInteger('statut_test')->nullable(false)->default(1)->comment('1: non fait; 2: en cours; 3: validé; 4: de coté par léquipe')->change();
            $table->dateTime('date_modification')->nullable()->change();
            $table->boolean('is_video')->nullable(false)->default(false)->change();
            $table->string('youtube_token', 20)->nullable()->change();
            $table->integer('reviewer_id')->nullable()->default(null)->change();
            $table->dateTime('reviewed_at')->nullable()->change();
        });

        DB::connection(self::FORMER_APP_DATABASE)->update(
            'UPDATE tests SET reviewer_id = NULL WHERE reviewer_id = 0'
        );

        Schema::connection(self::FORMER_APP_DATABASE)->table('tests', function (Blueprint $table) {
            $table->foreign('id_serie')
                ->references('id_serie')->on('series_tests');
            $table->foreign('id_jeu')
                ->references('id_jeu')->on('jeux');
            $table->foreign('id_jury')
                ->references('id_jury')->on('jury');
            $table->foreign('reviewer_id')
                ->references('id_membre')->on('membres');
        });

        DB::connection(self::FORMER_APP_DATABASE)->update(
            'UPDATE tests SET date_modification = NULL WHERE date_modification = "1901-03-21 23:59:11"'
        );
        DB::connection(self::FORMER_APP_DATABASE)->update(
            'UPDATE tests SET reviewed_at = NULL WHERE reviewed_at = "1901-03-21 23:59:11"'
        );

        // tests_average_char

        Schema::connection(self::FORMER_APP_DATABASE)->table('tests_average_char', function (Blueprint $table) {
            $table->integer('id_test')->change();
            $table->integer('average_char')->change();

            $table->foreign('id_test')
                ->references('id_test')->on('tests');
        });

        // tests_feedbacks

        Schema::connection(self::FORMER_APP_DATABASE)->table('tests_feedbacks', function (Blueprint $table) {
            $table->integer('id_test')->change();
            $table->integer('id_membre')->change();

            $table->smallInteger('note')->nullable(false)->change();
            $table->dateTime('date')->nullable(false)->change();

            $table->foreign('id_test')
                ->references('id_test')->on('tests');
            $table->foreign('id_membre')
                ->references('id_membre')->on('membres');
        });

        // equipe

        Schema::connection(self::FORMER_APP_DATABASE)->table('equipe', function (Blueprint $table) {
            $table->string('pseudo')->nullable()->change();
            $table->string('role')->nullable()->change();
            $table->smallInteger('type_role')->nullable(false)->change()->comment('1: président; 2: chef jurés; 3: respo site web; 4: communication externe; 5: ambassadeur; 6: illustrateur; 7: aide; 8: meilleur juré');
            $table->smallInteger('ordre')->nullable(false)->change();
            $table->boolean('display_on_team_page')->nullable(false)->default(true)->change();
            $table->integer('id_membre')->nullable()->change();
            $table->integer('id_session')->change();

            $table->foreign('id_session')
                ->references('id_session')->on('sessions');
            $table->foreign('id_membre')
                ->references('id_membre')->on('membres');
        });

        // awards_categories

        DB::connection(self::FORMER_APP_DATABASE)->statement(
            'ALTER TABLE awards_categories ENGINE = InnoDB'
        );

        Schema::connection(self::FORMER_APP_DATABASE)->table('awards_categories', function(Blueprint $table)
        {
            $table->integer('id_categorie', true)->change();
        });

        // awards_medias

        DB::connection(self::FORMER_APP_DATABASE)->statement(
            'ALTER TABLE awards_medias ENGINE = InnoDB'
        );

        DB::connection(self::FORMER_APP_DATABASE)->delete(
            'DELETE FROM awards_medias WHERE id_categorie NOT IN (SELECT id_categorie FROM awards_categories)'
        );

        Schema::connection(self::FORMER_APP_DATABASE)->table('awards_medias', function (Blueprint $table) {
            $table->integer('id_jeu')->nullable()->change();
            $table->integer('id_categorie')->nullable(false)->change();

            $table->integer('id_artiste')->nullable()->change();
            $table->string('pseudo_artiste')->nullable()->change();
            $table->boolean('anonymat_artiste')->nullable(false)->default(false)->comment('0: non; 1: oui')->change();
            $table->dateTime('date_ajout_media')->nullable()->change();
            $table->smallInteger('statut_media')->nullable(false)->default(2)->change();
            $table->smallInteger('type_media')->nullable(false)->default(1)->comment('0: ???, 1: image, 2: vidéo, 3: mp3')->change();
            $table->string('url_media')->nullable()->change();
            $table->text('description_media')->nullable()->change();
            $table->smallInteger('declinaison_actuelle')->nullable()->comment('1: or, 2: argent, 3: bronze')->change();
            $table->boolean('is_placeholder')->nullable(false)->default(false)->comment('Image temporaire en attendant le vrai award')->change();
        });

        DB::connection(self::FORMER_APP_DATABASE)->update(
            'UPDATE awards_medias SET id_jeu = NULL WHERE id_jeu = 0'
        );
        DB::connection(self::FORMER_APP_DATABASE)->update(
            'UPDATE awards_medias SET id_artiste = NULL WHERE id_artiste = 0'
        );

        Schema::connection(self::FORMER_APP_DATABASE)->table('awards_medias', function (Blueprint $table) {
            $table->foreign('id_jeu')
                ->references('id_jeu')->on('jeux');
            $table->foreign('id_artiste')
                ->references('id_membre')->on('membres');
            $table->foreign('id_categorie')
                ->references('id_categorie')->on('awards_categories');
        });

        // news

        DB::connection(self::FORMER_APP_DATABASE)->statement(
            'ALTER TABLE news ENGINE = InnoDB'
        );

        Schema::connection(self::FORMER_APP_DATABASE)->table('news', function (Blueprint $table) {
            $table->string('nom_news')->nullable()->change();
            $table->text('contenu_news')->nullable()->change();
            $table->integer('statut_news')->nullable(false)->default(1)->change();
            $table->dateTime('date_creation_news')->nullable()->change();
            $table->integer('nb_commentaires')->nullable(false)->default(0)->change();
            $table->smallInteger('origine')->nullable(false)->default(4)->change();
            $table->boolean('is_blog')->nullable(false)->default(false)->change();

            $table->dropColumn('date_validation_news');

            $table->foreign('id_membre')
                ->references('id_membre')->on('membres');
        });

        // commentaires

        DB::connection(self::FORMER_APP_DATABASE)->update(
            'UPDATE commentaires SET date_edition = "1901-03-21 23:59:11" WHERE date_edition = 0'
        );
        DB::connection(self::FORMER_APP_DATABASE)->delete(
            'DELETE FROM commentaires WHERE id_membre > 0 AND id_membre NOT IN (SELECT id_membre FROM membres)'
        );

        DB::connection(self::FORMER_APP_DATABASE)->statement(
            'ALTER TABLE commentaires ENGINE = InnoDB'
        );

        Schema::connection(self::FORMER_APP_DATABASE)->table('commentaires', function (Blueprint $table) {
            $table->integer('id_news')->nullable(false)->change();
            $table->integer('id_membre')->nullable()->change();
            $table->smallInteger('statut_commentaire')->nullable(false)->change();
            $table->string('titre_commentaire')->nullable()->change();
            $table->text('contenu_commentaire')->nullable()->change();
            $table->string('pseudo_commentaire')->nullable()->change();
            $table->dateTime('date_publication')->nullable()->change();
            $table->dateTime('date_edition')->nullable()->change();
            $table->integer('nombre_edition')->nullable(false)->default(0)->change();
            $table->boolean('is_commentaire_jeu')->nullable(false)->default(false)->change();
            $table->boolean('is_entre_jury')->nullable(false)->default(false)->change();
        });

        DB::connection(self::FORMER_APP_DATABASE)->delete(
            'UPDATE commentaires SET id_membre = NULL WHERE id_membre = 0'
        );

        Schema::connection(self::FORMER_APP_DATABASE)->table('commentaires', function (Blueprint $table) {
            // TODO : Use Morph associations
            //   $table->foreign('id_news')
            //       ->references('id_jeu')->on('jeux');
            //   $table->foreign('id_news')
            //       ->references('id_news')->on('news');
            $table->foreign('id_membre')
                ->references('id_membre')->on('membres');
        });

        DB::connection(self::FORMER_APP_DATABASE)->update(
            'UPDATE commentaires SET date_edition = NULL WHERE date_edition = "1901-03-21 23:59:11"'
        );

        // forum_categories

        DB::connection(self::FORMER_APP_DATABASE)->statement(
            'ALTER TABLE forum_categories ENGINE = InnoDB'
        );

        Schema::connection(self::FORMER_APP_DATABASE)->table('forum_categories', function (Blueprint $table) {
            $table->string('nom_categorie')->nullable()->change();
            $table->integer('statut_categorie')->nullable(false)->default(1)->change();
            $table->integer('permission')->nullable(false)->default(0)->change();
            $table->integer('position')->nullable(false)->default(1)->change();
        });

        // forum_messages (partie 1)

        DB::connection(self::FORMER_APP_DATABASE)->update(
            'UPDATE forum_messages SET date_edition = "1901-03-21 23:59:11" WHERE date_edition = 0'
        );
        DB::connection(self::FORMER_APP_DATABASE)->update(
            'UPDATE forum_messages SET date_dernier_message = "1901-03-21 23:59:11" WHERE date_dernier_message = 0'
        );

        DB::connection(self::FORMER_APP_DATABASE)->statement(
            'ALTER TABLE forum_messages ENGINE = InnoDB'
        );

        // forum_forums

        DB::connection(self::FORMER_APP_DATABASE)->statement(
            'ALTER TABLE forum_forums ENGINE = InnoDB'
        );
        DB::connection(self::FORMER_APP_DATABASE)->delete(
            'DELETE FROM forum_forums WHERE id_categorie NOT IN (SELECT id_categorie FROM forum_categories)'
        );

        Schema::connection(self::FORMER_APP_DATABASE)->table('forum_forums', function (Blueprint $table) {
            $table->integer('statut_forum')->change();
            $table->integer('position_forum')->change();
            $table->string('titre_forum')->nullable()->change();
            $table->string('sous_titre_forum')->nullable()->change();
            $table->integer('permission_forum')->nullable(false)->default(0)->change();
            $table->integer('nombre_sujets')->nullable(false)->default(0)->change();
            $table->integer('nombre_messages')->nullable(false)->default(0)->change();
            $table->integer('id_dernier_message_forum')->nullable()->change();
            $table->integer('parent_forum_id')->nullable()->change();
        });

        DB::connection(self::FORMER_APP_DATABASE)->update(
            'UPDATE forum_forums SET id_dernier_message_forum = NULL WHERE id_dernier_message_forum = 0'
        );
        DB::connection(self::FORMER_APP_DATABASE)->update(
            'UPDATE forum_forums SET parent_forum_id = NULL WHERE parent_forum_id = 0'
        );

        Schema::connection(self::FORMER_APP_DATABASE)->table('forum_forums', function (Blueprint $table) {
            $table->foreign('id_categorie')
                ->references('id_categorie')->on('forum_categories');
            $table->foreign('id_dernier_message_forum')
                ->references('id_message')->on('forum_messages');
            $table->foreign('parent_forum_id')
                ->references('id_forum')->on('forum_forums');
        });

        // forum_messages (partie 2)

        Schema::connection(self::FORMER_APP_DATABASE)->table('forum_messages', function (Blueprint $table) {
            $table->integer('id_sujet')->nullable(false)->default(0)->change();
            $table->integer('type_message')->nullable(false)->default(0)->comment('0: normal; 1: first; 2: first&sticky')->change();
            $table->integer('statut_message')->nullable(false)->default(1)->change();
            $table->string('titre_message')->nullable()->change();
            $table->string('sous_titre_message')->nullable()->change();
            $table->text('contenu_message')->nullable()->change();
            $table->dateTime('date_publication')->nullable()->change();
            $table->dateTime('date_edition')->nullable()->change();
            $table->dateTime('date_dernier_message')->nullable()->change();
            $table->integer('nombre_edition')->nullable(false)->default(0)->change();
            $table->integer('nombre_reponses')->nullable(false)->default(0)->change();
            $table->integer('nombre_visites')->nullable(false)->default(0)->change();
            $table->integer('dernier_reponse')->nullable()->change();
        });

        DB::connection(self::FORMER_APP_DATABASE)->update(
            'UPDATE forum_messages SET dernier_reponse = NULL WHERE dernier_reponse = 0'
        );

        Schema::connection(self::FORMER_APP_DATABASE)->table('forum_messages', function (Blueprint $table) {
            $table->foreign('id_forum')
                ->references('id_forum')->on('forum_forums');
            $table->foreign('id_membre')
                ->references('id_membre')->on('membres');
            $table->foreign('dernier_reponse')
                ->references('id_message')->on('forum_messages');
        });

        DB::connection(self::FORMER_APP_DATABASE)->update(
            'UPDATE forum_messages SET date_edition = NULL WHERE date_edition = "1901-03-21 23:59:11"'
        );
        DB::connection(self::FORMER_APP_DATABASE)->update(
            'UPDATE forum_messages SET date_dernier_message = NULL WHERE date_dernier_message = "1901-03-21 23:59:11"'
        );

        // connexions

        DB::connection(self::FORMER_APP_DATABASE)->statement(
            'ALTER TABLE connexions ENGINE = InnoDB'
        );

        Schema::connection(self::FORMER_APP_DATABASE)->table('connexions', function (Blueprint $table) {
            $table->integer('id_membre')->nullable()->change();
            $table->integer('id_visiteur')->nullable(false)->default(0)->change();
            $table->string('ip')->nullable()->change();
            $table->boolean('no_session')->nullable(false)->default(0)->change();
            $table->dateTime('date_connexion')->nullable()->change();
            $table->dateTime('date_expiration')->nullable()->change();
            $table->text('position')->nullable()->change();
        });

        DB::connection(self::FORMER_APP_DATABASE)->update(
            'UPDATE connexions SET id_membre = NULL WHERE id_membre = 0'
        );

        Schema::connection(self::FORMER_APP_DATABASE)->table('connexions', function (Blueprint $table) {
            $table->foreign('id_membre')
                ->references('id_membre')->on('membres');
        });

        // deliberations_commentaires

        Schema::connection(self::FORMER_APP_DATABASE)->table('deliberations_commentaires', function (Blueprint $table) {
            $table->integer('id_categorie')->nullable(false)->change();

            $table->integer('id_jury')->nullable()->change();
            $table->integer('id_membre')->nullable()->change();

            $table->text('contenu_deliberation')->nullable()->change();
        });

        DB::connection(self::FORMER_APP_DATABASE)->update(
            'UPDATE deliberations_commentaires SET id_jury = NULL WHERE id_jury = 0'
        );
        DB::connection(self::FORMER_APP_DATABASE)->update(
            'UPDATE deliberations_commentaires SET id_membre = NULL WHERE id_membre = 0'
        );

        Schema::connection(self::FORMER_APP_DATABASE)->table('deliberations_commentaires', function (Blueprint $table) {
            $table->foreign('id_membre')
                ->references('id_membre')->on('membres');
            $table->foreign('id_jury')
                ->references('id_jury')->on('jury');
            $table->foreign('id_categorie')
                ->references('id_categorie')->on('awards_categories');
        });

        // deliberations_notes

        Schema::connection(self::FORMER_APP_DATABASE)->table('deliberations_notes', function (Blueprint $table) {
            $table->integer('id_jeu')->nullable(false)->change();
            $table->integer('id_categorie')->nullable(false)->change();

            $table->integer('id_jury')->nullable()->change();
            $table->integer('id_membre')->nullable()->change();

            $table->smallInteger('note')->nullable()->change();
            $table->decimal('note_coef', 10)->nullable()->change();
            $table->smallInteger('position')->nullable(false)->default(0)->change();
        });

        DB::connection(self::FORMER_APP_DATABASE)->update(
            'UPDATE deliberations_notes SET id_jury = NULL WHERE id_jury = 0'
        );
        DB::connection(self::FORMER_APP_DATABASE)->update(
            'UPDATE deliberations_notes SET id_membre = NULL WHERE id_membre = 0'
        );

        Schema::connection(self::FORMER_APP_DATABASE)->table('deliberations_notes', function (Blueprint $table) {
            $table->foreign('id_membre')
                ->references('id_membre')->on('membres');
            $table->foreign('id_jury')
                ->references('id_jury')->on('jury');
            $table->foreign('id_jeu')
                ->references('id_jeu')->on('jeux');
            $table->foreign('id_categorie')
                ->references('id_categorie')->on('awards_categories');
        });

        // deliberations_suffisamment_joue

        Schema::connection(self::FORMER_APP_DATABASE)->table('deliberations_suffisamment_joue', function (Blueprint $table) {
            $table->integer('id_jeu')->nullable(false)->change();

            $table->integer('id_jury')->nullable()->change();
            $table->integer('id_membre')->nullable()->change();

            $table->boolean('suffisamment_joue')->nullable(false)->default(false)->change();
        });

        DB::connection(self::FORMER_APP_DATABASE)->update(
            'UPDATE deliberations_suffisamment_joue SET id_jury = NULL WHERE id_jury = 0'
        );
        DB::connection(self::FORMER_APP_DATABASE)->update(
            'UPDATE deliberations_suffisamment_joue SET id_membre = NULL WHERE id_membre = 0'
        );

        Schema::connection(self::FORMER_APP_DATABASE)->table('deliberations_suffisamment_joue', function (Blueprint $table) {
            $table->foreign('id_membre')
                ->references('id_membre')->on('membres');
            $table->foreign('id_jury')
                ->references('id_jury')->on('jury');
            $table->foreign('id_jeu')
                ->references('id_jeu')->on('jeux');
        });

        // guestbook

        DB::connection(self::FORMER_APP_DATABASE)->update(
            'UPDATE guestbook SET date_signature = "1901-03-21 23:59:11" WHERE date_signature = 0'
        );

        DB::connection(self::FORMER_APP_DATABASE)->statement(
            'ALTER TABLE guestbook ENGINE = InnoDB'
        );

        Schema::connection(self::FORMER_APP_DATABASE)->table('guestbook', function (Blueprint $table) {
            $table->integer('id_membre')->nullable()->change();
            $table->dateTime('date_signature')->nullable()->change();
        });

        DB::connection(self::FORMER_APP_DATABASE)->update(
            'UPDATE guestbook SET id_membre = NULL WHERE id_membre = 0'
        );

        Schema::connection(self::FORMER_APP_DATABASE)->table('guestbook', function (Blueprint $table) {
            $table->foreign('id_membre')
                ->references('id_membre')->on('membres');
        });

        DB::connection(self::FORMER_APP_DATABASE)->update(
            'UPDATE guestbook SET date_signature = NULL WHERE date_signature = "1901-03-21 23:59:11"'
        );

        // images

        Schema::connection(self::FORMER_APP_DATABASE)->table('images', function (Blueprint $table) {
            $table->integer('id_membre')->nullable(false)->change();

            $table->foreign('id_membre')
                ->references('id_membre')->on('membres');
        });

        // jeux_uploads

        Schema::connection(self::FORMER_APP_DATABASE)->table('jeux_uploads', function (Blueprint $table) {
            $table->integer('id_membre')->nullable(false)->change();

            $table->foreign('id_membre')
                ->references('id_membre')->on('membres');
        });

        // jukebox

        DB::connection(self::FORMER_APP_DATABASE)->statement(
            'ALTER TABLE jukebox ENGINE = InnoDB'
        );

        Schema::connection(self::FORMER_APP_DATABASE)->table('jukebox', function (Blueprint $table) {
            $table->integer('id_posteur')->nullable(false)->change();
            $table->integer('id_jeu_origine')->nullable(false)->change();

            $table->foreign('id_posteur')
                ->references('id_membre')->on('membres');
            $table->foreign('id_jeu_origine')
                ->references('id_jeu')->on('jeux');
        });

        // lus

        DB::connection(self::FORMER_APP_DATABASE)->statement(
            'ALTER TABLE lus ENGINE = InnoDB'
        );

        DB::connection(self::FORMER_APP_DATABASE)->delete(
            'DELETE FROM lus WHERE id_dernier_message_lu NOT IN (SELECT id_message FROM forum_messages)'
        );

        Schema::connection(self::FORMER_APP_DATABASE)->table('lus', function (Blueprint $table) {
            $table->integer('id_dernier_message_lu')->nullable()->change();
        });

        DB::connection(self::FORMER_APP_DATABASE)->update(
            'UPDATE lus SET id_dernier_message_lu = NULL WHERE id_dernier_message_lu = 0'
        );

        Schema::connection(self::FORMER_APP_DATABASE)->table('lus', function (Blueprint $table) {
            $table->foreign('id_forum')
                ->references('id_forum')->on('forum_forums');
            $table->foreign('id_dernier_message_lu')
                ->references('id_message')->on('forum_messages');
            $table->foreign('id_dernier_message_poste')
                ->references('id_message')->on('forum_messages');
        });

        // mail

        DB::connection(self::FORMER_APP_DATABASE)->statement(
            'ALTER TABLE mail ENGINE = InnoDB'
        );

        DB::connection(self::FORMER_APP_DATABASE)->delete(
            'DELETE FROM mail WHERE id_membre_destinataire NOT IN (SELECT id_membre FROM membres)'
        );

        Schema::connection(self::FORMER_APP_DATABASE)->table('mail', function (Blueprint $table) {
            $table->integer('id_membre_expediteur')->nullable()->change();
            $table->integer('id_membre_destinataire')->nullable()->change();
            $table->integer('id_session_destinataire')->nullable()->change();
        });

        DB::connection(self::FORMER_APP_DATABASE)->update(
            'UPDATE mail SET id_membre_expediteur = NULL WHERE id_membre_expediteur = 0'
        );
        DB::connection(self::FORMER_APP_DATABASE)->update(
            'UPDATE mail SET id_membre_destinataire = NULL WHERE id_membre_destinataire = 0'
        );
        DB::connection(self::FORMER_APP_DATABASE)->update(
            'UPDATE mail SET id_session_destinataire = NULL WHERE id_session_destinataire = 0'
        );

        Schema::connection(self::FORMER_APP_DATABASE)->table('mail', function (Blueprint $table) {
            $table->foreign('id_membre_expediteur')
                ->references('id_membre')->on('membres');
            $table->foreign('id_membre_destinataire')
                ->references('id_membre')->on('membres');
            $table->foreign('id_session_destinataire')
                ->references('id_session')->on('sessions');
        });

        // mdp_remake

        DB::connection(self::FORMER_APP_DATABASE)->statement(
            'ALTER TABLE mdp_remake ENGINE = InnoDB'
        );
        DB::connection(self::FORMER_APP_DATABASE)->delete(
            'DELETE FROM mdp_remake WHERE id_mb NOT IN (SELECT id_membre FROM membres)'
        );

        Schema::connection(self::FORMER_APP_DATABASE)->table('mdp_remake', function (Blueprint $table) {
            $table->integer('id_mb')->nullable()->change();

            $table->foreign('id_mb')
                ->references('id_membre')->on('membres');
        });

        // mp

        DB::connection(self::FORMER_APP_DATABASE)->update(
            'UPDATE mp SET date_edition = "1901-03-21 23:59:11" WHERE date_edition = 0'
        );
        DB::connection(self::FORMER_APP_DATABASE)->update(
            'UPDATE mp SET date_dernier_message = "1901-03-21 23:59:11" WHERE date_dernier_message = 0'
        );

        DB::connection(self::FORMER_APP_DATABASE)->statement(
            'ALTER TABLE mp ENGINE = InnoDB'
        );

        DB::connection(self::FORMER_APP_DATABASE)->delete(
            'DELETE FROM mp WHERE id_destinateur NOT IN (SELECT id_membre FROM membres)'
        );
        DB::connection(self::FORMER_APP_DATABASE)->delete(
            'DELETE FROM mp WHERE id_destinataire NOT IN (SELECT id_membre FROM membres)'
        );

        Schema::connection(self::FORMER_APP_DATABASE)->table('mp', function (Blueprint $table) {
            $table->integer('nombre_edition')->nullable(false)->default(0)->change();
            $table->boolean('lu')->nullable(false)->default(false)->change();
            $table->dateTime('date_edition')->nullable()->change();
            $table->dateTime('date_dernier_message')->nullable()->change();

            $table->foreign('id_destinateur')
                ->references('id_membre')->on('membres');
            $table->foreign('id_destinataire')
                ->references('id_membre')->on('membres');
        });

        DB::connection(self::FORMER_APP_DATABASE)->update(
            'UPDATE mp SET date_edition = NULL WHERE date_edition = "1901-03-21 23:59:11"'
        );
        DB::connection(self::FORMER_APP_DATABASE)->update(
            'UPDATE mp SET date_dernier_message = NULL WHERE date_dernier_message = "1901-03-21 23:59:11"'
        );

        // news_categories_pivot

        DB::connection(self::FORMER_APP_DATABASE)->delete(
            'DELETE FROM news_categories_pivot WHERE id_news = 0'
        );

        Schema::connection(self::FORMER_APP_DATABASE)->table('news_categories_pivot', function (Blueprint $table) {
            $table->integer('id_news')->nullable(false)->change();

            $table->foreign('id_news')
                ->references('id_news')->on('news');
            $table->foreign('id_categorie')
                ->references('id_categorie')->on('news_categories');
        });

        // notes

        DB::connection(self::FORMER_APP_DATABASE)->delete(
            'DELETE FROM notes WHERE id_test NOT IN (SELECT id_test FROM tests)'
        );

        DB::connection(self::FORMER_APP_DATABASE)->statement(
            'ALTER TABLE mp ENGINE = InnoDB'
        );

        Schema::connection(self::FORMER_APP_DATABASE)->table('notes', function (Blueprint $table) {
            $table->integer('id_test')->nullable()->change();

            $table->foreign('id_test')
                ->references('id_test')->on('tests');
        });

        // partenariat

        Schema::connection(self::FORMER_APP_DATABASE)->table('partenariat', function (Blueprint $table) {
            $table->boolean('valide')->nullable(false)->default(false)->change();
            $table->boolean('traite')->nullable(false)->default(false)->change();
        });

        // phrases_exergue

        DB::connection(self::FORMER_APP_DATABASE)->statement(
            'ALTER TABLE phrases_exergue ENGINE = InnoDB'
        );

        Schema::connection(self::FORMER_APP_DATABASE)->table('phrases_exergue', function (Blueprint $table) {
            $table->integer('id_membre')->nullable(false)->change();

            $table->foreign('id_membre')
                ->references('id_membre')->on('membres');
        });

        // pre_tests_categories

        Schema::connection(self::FORMER_APP_DATABASE)->table('pre_tests_categories', function (Blueprint $table) {
            $table->integer('id_jeu')->nullable(false)->change();
            $table->integer('id_jury')->nullable(false)->change();
            $table->integer('id_serie')->nullable(false)->change();
            $table->integer('id_categorie')->nullable(false)->change();

            $table->smallInteger('statut_ptc')->nullable(false)->default(0)->change();

            $table->foreign('id_serie')
                ->references('id_serie')->on('series_tests');
            $table->foreign('id_jury')
                ->references('id_jury')->on('jury');
            $table->foreign('id_jeu')
                ->references('id_jeu')->on('jeux');
            $table->foreign('id_categorie')
                ->references('id_categorie')->on('awards_categories');
        });

        // recap

        DB::connection(self::FORMER_APP_DATABASE)->statement(
            'ALTER TABLE recap ENGINE = InnoDB'
        );

        Schema::connection(self::FORMER_APP_DATABASE)->table('recap', function (Blueprint $table) {
            $table->integer('id_membre')->nullable()->change();
        });

        DB::connection(self::FORMER_APP_DATABASE)->update(
            'UPDATE recap SET id_membre = NULL WHERE id_membre = 0'
        );

        Schema::connection(self::FORMER_APP_DATABASE)->table('recap', function (Blueprint $table) {
            $table->foreign('id_membre')
                ->references('id_membre')->on('membres');
        });

        // recherches

        DB::connection(self::FORMER_APP_DATABASE)->statement(
            'ALTER TABLE recherches ENGINE = InnoDB'
        );

        Schema::connection(self::FORMER_APP_DATABASE)->table('recherches', function (Blueprint $table) {
            $table->integer('id_membre')->nullable()->change();
        });

        DB::connection(self::FORMER_APP_DATABASE)->update(
            'UPDATE recherches SET id_membre = NULL WHERE id_membre = 0'
        );

        Schema::connection(self::FORMER_APP_DATABASE)->table('recherches', function (Blueprint $table) {
            $table->foreign('id_membre')
                ->references('id_membre')->on('membres');
        });

        // recrutement

        Schema::connection(self::FORMER_APP_DATABASE)->table('recrutement', function (Blueprint $table) {
            $table->integer('id_membre')->nullable(false)->change();

            $table->foreign('id_membre')
                ->references('id_membre')->on('membres');
        });

        // reglements

        DB::connection(self::FORMER_APP_DATABASE)->statement(
            'ALTER TABLE reglements ENGINE = InnoDB'
        );

        Schema::connection(self::FORMER_APP_DATABASE)->table('reglements', function (Blueprint $table) {
            $table->foreign('id_session')
                ->references('id_session')->on('sessions');
        });

        // screenshots_du_moment

        DB::connection(self::FORMER_APP_DATABASE)->statement(
            'ALTER TABLE screenshots_du_moment ENGINE = InnoDB'
        );

        Schema::connection(self::FORMER_APP_DATABASE)->table('screenshots_du_moment', function (Blueprint $table) {
            $table->integer('id_membre')->nullable(false)->change();

            $table->foreign('id_membre')
                ->references('id_membre')->on('membres');
        });

        // videos_du_moment

        DB::connection(self::FORMER_APP_DATABASE)->statement(
            'ALTER TABLE videos_du_moment ENGINE = InnoDB'
        );

        Schema::connection(self::FORMER_APP_DATABASE)->table('videos_du_moment', function (Blueprint $table) {
            $table->integer('id_membre')->nullable(false)->change();

            $table->foreign('id_membre')
                ->references('id_membre')->on('membres');
        });

        // sondages

        DB::connection(self::FORMER_APP_DATABASE)->statement(
            'ALTER TABLE sondages ENGINE = InnoDB'
        );

        Schema::connection(self::FORMER_APP_DATABASE)->table('sondages', function (Blueprint $table) {
            $table->string('question')->nullable(false)->change();
            $table->smallInteger('nb_reponses')->nullable(false)->default(2)->change();
            $table->smallInteger('votes1')->nullable(false)->default(0)->change();
            $table->smallInteger('nb_votes')->nullable(false)->default(0)->change();
            $table->smallInteger('statut_sondage')->default(1)->comment('0: supprimé; 1: actif; 2: archivé;')->change();;
            $table->boolean('multiple')->nullable(false)->default(false)->change();
        });

        // sondages_id

        DB::connection(self::FORMER_APP_DATABASE)->statement(
            'ALTER TABLE sondages_id ENGINE = InnoDB'
        );

        Schema::connection(self::FORMER_APP_DATABASE)->table('sondages_id', function (Blueprint $table) {
            $table->integer('id_membre')->nullable(false)->change();

            $table->foreign('id_membre')
                ->references('id_membre')->on('membres');
            $table->foreign('id_sondage')
                ->references('id_sondage')->on('sondages');
        });

        // telechargements

        DB::connection(self::FORMER_APP_DATABASE)->statement(
            'ALTER TABLE telechargements ENGINE = InnoDB'
        );

        DB::connection(self::FORMER_APP_DATABASE)->delete(
            'DELETE FROM telechargements WHERE id_membre > 0 AND id_membre NOT IN (SELECT id_membre FROM membres)'
        );

        Schema::connection(self::FORMER_APP_DATABASE)->table('telechargements', function (Blueprint $table) {
            $table->integer('id_membre')->nullable()->change();
            $table->integer('id_jeu')->nullable(false)->change();
        });

        DB::connection(self::FORMER_APP_DATABASE)->update(
            'UPDATE telechargements SET id_membre = NULL WHERE id_membre = 0'
        );

        Schema::connection(self::FORMER_APP_DATABASE)->table('telechargements', function (Blueprint $table) {
            $table->foreign('id_membre')
                ->references('id_membre')->on('membres');
            $table->foreign('id_jeu')
                ->references('id_jeu')->on('jeux');
        });

        // vote

        DB::connection(self::FORMER_APP_DATABASE)->update(
            'UPDATE vote SET date_vote = "1901-03-21 23:59:11" WHERE date_vote = 0'
        );

        DB::connection(self::FORMER_APP_DATABASE)->statement(
            'ALTER TABLE vote ENGINE = InnoDB'
        );

        Schema::connection(self::FORMER_APP_DATABASE)->table('vote', function (Blueprint $table) {
            $table->dateTime('date_vote')->nullable()->change();

            $table->foreign('id_membre')
                ->references('id_membre')->on('membres');
            $table->foreign('id_jeu')
                ->references('id_jeu')->on('jeux');
            $table->foreign('id_session')
                ->references('id_session')->on('sessions');
        });

        DB::connection(self::FORMER_APP_DATABASE)->update(
            'UPDATE vote SET date_vote = NULL WHERE date_vote = "1901-03-21 23:59:11"'
        );
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::connection(self::FORMER_APP_DATABASE)->hasTable('series_jeux')) {
            Schema::connection(self::FORMER_APP_DATABASE)->table('series_jeux', function (Blueprint $table) {
                $table->dropIndex('series_jeux_id_serie_index');
            });
        }

        if (Schema::connection(self::FORMER_APP_DATABASE)->hasTable('jeux')) {
            Schema::connection(self::FORMER_APP_DATABASE)->table('jeux', function (Blueprint $table) {
                $table->dropForeign(['id_session']);
                $table->dropForeign(['id_serie_jeu']);
            });
        }

        if (Schema::connection(self::FORMER_APP_DATABASE)->hasTable('participants')) {
            Schema::connection(self::FORMER_APP_DATABASE)->table('participants', function (Blueprint $table) {
                $table->dropForeign(['id_jeu']);
                $table->dropForeign(['id_membre']);
            });
        }

        if (Schema::connection(self::FORMER_APP_DATABASE)->hasTable('screenshots')) {
            Schema::connection(self::FORMER_APP_DATABASE)->table('screenshots', function (Blueprint $table) {
                $table->dropForeign(['id_jeu']);
            });
        }

        if (Schema::connection(self::FORMER_APP_DATABASE)->hasTable('nomines')) {
            Schema::connection(self::FORMER_APP_DATABASE)->table('nomines', function (Blueprint $table) {
                $table->dropForeign(['id_jeu']);
                $table->dropForeign(['id_categorie']);
            });
        }

        if (Schema::connection(self::FORMER_APP_DATABASE)->hasTable('jeux_favoris')) {
            Schema::connection(self::FORMER_APP_DATABASE)->table('jeux_favoris', function (Blueprint $table) {
                $table->dropForeign(['id_jeu']);
                $table->dropForeign(['id_membre']);
            });
        }

        if (Schema::connection(self::FORMER_APP_DATABASE)->hasTable('jury')) {
            Schema::connection(self::FORMER_APP_DATABASE)->table('jury', function (Blueprint $table) {
                $table->dropForeign(['id_session']);
                $table->dropForeign(['id_membre']);
            });
        }

        if (Schema::connection(self::FORMER_APP_DATABASE)->hasTable('series_tests')) {
            Schema::connection(self::FORMER_APP_DATABASE)->table('series_tests', function (Blueprint $table) {
                $table->dropForeign(['id_session']);
            });
        }

        if (Schema::connection(self::FORMER_APP_DATABASE)->hasTable('series_tests_jeux')) {
            Schema::connection(self::FORMER_APP_DATABASE)->table('series_tests_jeux', function (Blueprint $table) {
                $table->dropForeign(['id_jeu']);
                $table->dropForeign(['id_serie']);
            });
        }

        if (Schema::connection(self::FORMER_APP_DATABASE)->hasTable('series_tests_jeux_jures')) {
            Schema::connection(self::FORMER_APP_DATABASE)->table('series_tests_jeux_jures', function (Blueprint $table) {
                $table->dropForeign(['id_jeu']);
                $table->dropForeign(['id_jury']);
                $table->dropForeign(['id_serie']);
            });
        }

        if (Schema::connection(self::FORMER_APP_DATABASE)->hasTable('tests')) {
            Schema::connection(self::FORMER_APP_DATABASE)->table('tests', function (Blueprint $table) {
                $table->dropForeign(['id_jeu']);
                $table->dropForeign(['id_jury']);
                $table->dropForeign(['id_serie']);
                $table->dropForeign(['reviewer_id']);
            });
        }

        if (Schema::connection(self::FORMER_APP_DATABASE)->hasTable('tests_average_char')) {
            Schema::connection(self::FORMER_APP_DATABASE)->table('tests_average_char', function (Blueprint $table) {
                $table->dropForeign(['id_test']);
            });
        }

        if (Schema::connection(self::FORMER_APP_DATABASE)->hasTable('tests_feedbacks')) {
            Schema::connection(self::FORMER_APP_DATABASE)->table('tests_feedbacks', function (Blueprint $table) {
                $table->dropForeign(['id_test']);
                $table->dropForeign(['id_membre']);
            });
        }

        if (Schema::connection(self::FORMER_APP_DATABASE)->hasTable('equipe')) {
            Schema::connection(self::FORMER_APP_DATABASE)->table('equipe', function (Blueprint $table) {
                $table->dropForeign(['id_session']);
                $table->dropForeign(['id_membre']);
            });
        }

        if (Schema::connection(self::FORMER_APP_DATABASE)->hasTable('awards_medias')) {
            Schema::connection(self::FORMER_APP_DATABASE)->table('awards_medias', function (Blueprint $table) {
                $table->dropForeign(['id_jeu']);
                $table->dropForeign(['id_artiste']);
                $table->dropForeign(['id_categorie']);
            });
        }

        if (Schema::connection(self::FORMER_APP_DATABASE)->hasTable('news')) {
            Schema::connection(self::FORMER_APP_DATABASE)->table('news', function (Blueprint $table) {
                $table->dropForeign(['id_membre']);
            });
        }

        if (Schema::connection(self::FORMER_APP_DATABASE)->hasTable('commentaires')) {
            Schema::connection(self::FORMER_APP_DATABASE)->table('commentaires', function (Blueprint $table) {
                $table->dropForeign(['id_membre']);
            });
        }

        if (Schema::connection(self::FORMER_APP_DATABASE)->hasTable('forum_forums')) {
            Schema::connection(self::FORMER_APP_DATABASE)->table('forum_forums', function (Blueprint $table) {
                $table->dropForeign(['id_categorie']);
                $table->dropForeign(['id_dernier_message_forum']);
                $table->dropForeign(['parent_forum_id']);
            });
        }

        if (Schema::connection(self::FORMER_APP_DATABASE)->hasTable('forum_messages')) {
            Schema::connection(self::FORMER_APP_DATABASE)->table('forum_messages', function (Blueprint $table) {
                $table->dropForeign(['id_forum']);
                $table->dropForeign(['id_membre']);
                $table->dropForeign(['dernier_reponse']);
            });
        }

        if (Schema::connection(self::FORMER_APP_DATABASE)->hasTable('connexions')) {
            Schema::connection(self::FORMER_APP_DATABASE)->table('connexions', function (Blueprint $table) {
                $table->dropForeign(['id_membre']);
            });
        }

        if (Schema::connection(self::FORMER_APP_DATABASE)->hasTable('deliberations_commentaires')) {
            Schema::connection(self::FORMER_APP_DATABASE)->table('deliberations_commentaires', function (Blueprint $table) {
                $table->dropForeign(['id_membre']);
                $table->dropForeign(['id_jury']);
                $table->dropForeign(['id_categorie']);
            });
        }

        if (Schema::connection(self::FORMER_APP_DATABASE)->hasTable('deliberations_notes')) {
            Schema::connection(self::FORMER_APP_DATABASE)->table('deliberations_notes', function (Blueprint $table) {
                $table->dropForeign(['id_membre']);
                $table->dropForeign(['id_jury']);
                $table->dropForeign(['id_jeu']);
                $table->dropForeign(['id_categorie']);
            });
        }

        if (Schema::connection(self::FORMER_APP_DATABASE)->hasTable('deliberations_suffisamment_joue')) {
            Schema::connection(self::FORMER_APP_DATABASE)->table('deliberations_suffisamment_joue', function (Blueprint $table) {
                $table->dropForeign(['id_membre']);
                $table->dropForeign(['id_jury']);
                $table->dropForeign(['id_jeu']);
            });
        }

        if (Schema::connection(self::FORMER_APP_DATABASE)->hasTable('guestbook')) {
            Schema::connection(self::FORMER_APP_DATABASE)->table('guestbook', function (Blueprint $table) {
                $table->dropForeign(['id_membre']);
            });
        }

        if (Schema::connection(self::FORMER_APP_DATABASE)->hasTable('images')) {
            Schema::connection(self::FORMER_APP_DATABASE)->table('images', function (Blueprint $table) {
                $table->dropForeign(['id_membre']);
            });
        }

        if (Schema::connection(self::FORMER_APP_DATABASE)->hasTable('jeux_uploads')) {
            Schema::connection(self::FORMER_APP_DATABASE)->table('jeux_uploads', function (Blueprint $table) {
                $table->dropForeign(['id_membre']);
            });
        }

        if (Schema::connection(self::FORMER_APP_DATABASE)->hasTable('jukebox')) {
            Schema::connection(self::FORMER_APP_DATABASE)->table('jukebox', function (Blueprint $table) {
                $table->dropForeign(['id_posteur']);
                $table->dropForeign(['id_jeu_origine']);
            });
        }

        if (Schema::connection(self::FORMER_APP_DATABASE)->hasTable('lus')) {
            Schema::connection(self::FORMER_APP_DATABASE)->table('lus', function (Blueprint $table) {
                $table->dropForeign(['id_forum']);
                $table->dropForeign(['id_dernier_message_lu']);
                $table->dropForeign(['id_dernier_message_poste']);
            });
        }

        if (Schema::connection(self::FORMER_APP_DATABASE)->hasTable('mail')) {
            Schema::connection(self::FORMER_APP_DATABASE)->table('mail', function (Blueprint $table) {
                $table->dropForeign(['id_membre_expediteur']);
                $table->dropForeign(['id_membre_destinataire']);
                $table->dropForeign(['id_session_destinataire']);
            });
        }

        if (Schema::connection(self::FORMER_APP_DATABASE)->hasTable('mdp_remake')) {
            Schema::connection(self::FORMER_APP_DATABASE)->table('mdp_remake', function (Blueprint $table) {
                $table->dropForeign(['id_mb']);
            });
        }

        if (Schema::connection(self::FORMER_APP_DATABASE)->hasTable('mp')) {
            Schema::connection(self::FORMER_APP_DATABASE)->table('mp', function (Blueprint $table) {
                $table->dropForeign(['id_destinateur']);
                $table->dropForeign(['id_destinataire']);
            });
        }

        if (Schema::connection(self::FORMER_APP_DATABASE)->hasTable('news_categories_pivot')) {
            Schema::connection(self::FORMER_APP_DATABASE)->table('news_categories_pivot', function (Blueprint $table) {
                $table->dropForeign(['id_news']);
                $table->dropForeign(['id_categorie']);
            });
        }

        if (Schema::connection(self::FORMER_APP_DATABASE)->hasTable('notes')) {
            Schema::connection(self::FORMER_APP_DATABASE)->table('notes', function (Blueprint $table) {
                $table->dropForeign(['id_test']);
            });
        }

        if (Schema::connection(self::FORMER_APP_DATABASE)->hasTable('phrases_exergue')) {
            Schema::connection(self::FORMER_APP_DATABASE)->table('phrases_exergue', function (Blueprint $table) {
                $table->dropForeign(['id_membre']);
            });
        }

        if (Schema::connection(self::FORMER_APP_DATABASE)->hasTable('pre_tests_categories')) {
            Schema::connection(self::FORMER_APP_DATABASE)->table('pre_tests_categories', function (Blueprint $table) {
                $table->dropForeign(['id_serie']);
                $table->dropForeign(['id_jury']);
                $table->dropForeign(['id_jeu']);
                $table->dropForeign(['id_categorie']);
            });
        }

        if (Schema::connection(self::FORMER_APP_DATABASE)->hasTable('recap')) {
            Schema::connection(self::FORMER_APP_DATABASE)->table('recap', function (Blueprint $table) {
                $table->dropForeign(['id_membre']);
            });
        }

        if (Schema::connection(self::FORMER_APP_DATABASE)->hasTable('recherches')) {
            Schema::connection(self::FORMER_APP_DATABASE)->table('recherches', function (Blueprint $table) {
                $table->dropForeign(['id_membre']);
            });
        }

        if (Schema::connection(self::FORMER_APP_DATABASE)->hasTable('recrutement')) {
            Schema::connection(self::FORMER_APP_DATABASE)->table('recrutement', function (Blueprint $table) {
                $table->dropForeign(['id_membre']);
            });
        }

        if (Schema::connection(self::FORMER_APP_DATABASE)->hasTable('reglements')) {
            Schema::connection(self::FORMER_APP_DATABASE)->table('reglements', function (Blueprint $table) {
                $table->dropForeign(['id_session']);
            });
        }

        if (Schema::connection(self::FORMER_APP_DATABASE)->hasTable('screenshots_du_moment')) {
            Schema::connection(self::FORMER_APP_DATABASE)->table('screenshots_du_moment', function (Blueprint $table) {
                $table->dropForeign(['id_membre']);
            });
        }

        if (Schema::connection(self::FORMER_APP_DATABASE)->hasTable('videos_du_moment')) {
            Schema::connection(self::FORMER_APP_DATABASE)->table('videos_du_moment', function (Blueprint $table) {
                $table->dropForeign(['id_membre']);
            });
        }

        if (Schema::connection(self::FORMER_APP_DATABASE)->hasTable('sondages_id')) {
            Schema::connection(self::FORMER_APP_DATABASE)->table('sondages_id', function (Blueprint $table) {
                $table->dropForeign(['id_membre']);
                $table->dropForeign(['id_sondage']);
            });
        }

        if (Schema::connection(self::FORMER_APP_DATABASE)->hasTable('telechargements')) {
            Schema::connection(self::FORMER_APP_DATABASE)->table('telechargements', function (Blueprint $table) {
                $table->dropForeign(['id_membre']);
                $table->dropForeign(['id_jeu']);
            });
        }

        if (Schema::connection(self::FORMER_APP_DATABASE)->hasTable('vote')) {
            Schema::connection(self::FORMER_APP_DATABASE)->table('vote', function (Blueprint $table) {
                $table->dropForeign(['id_membre']);
                $table->dropForeign(['id_jeu']);
                $table->dropForeign(['id_session']);
            });
        }
    }
}
