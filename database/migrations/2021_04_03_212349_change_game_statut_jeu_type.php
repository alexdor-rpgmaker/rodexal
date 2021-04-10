<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ChangeGameStatutJeuType extends Migration
{
    const FORMER_APP_DATABASE = 'former_app_database';

    public function up()
    {
        Schema::connection(self::FORMER_APP_DATABASE)->table('jeux', function (Blueprint $table) {
            $table->string('statut_jeu')->comment('')->nullable(false)->change();
        });

        DB::connection(self::FORMER_APP_DATABASE)->update(
            "UPDATE jeux SET statut_jeu = 'registered' WHERE id_session = 21"
        );

        DB::connection(self::FORMER_APP_DATABASE)->update(
            "UPDATE jeux SET statut_jeu = 'awarded' WHERE statut_jeu = '4'"
        );
        DB::connection(self::FORMER_APP_DATABASE)->update(
            "UPDATE jeux SET statut_jeu = 'nominated' WHERE statut_jeu = '3'"
        );
        DB::connection(self::FORMER_APP_DATABASE)->update(
            "UPDATE jeux SET statut_jeu = 'qualified' WHERE statut_jeu = '2'"
        );
        DB::connection(self::FORMER_APP_DATABASE)->update(
            "UPDATE jeux SET statut_jeu = 'disqualified' WHERE statut_jeu = '1'"
        );
        DB::connection(self::FORMER_APP_DATABASE)->update(
            "UPDATE jeux SET statut_jeu = 'deleted' WHERE statut_jeu = '0'"
        );
    }

    public function down()
    {
        DB::connection(self::FORMER_APP_DATABASE)->update(
            "UPDATE jeux SET statut_jeu = '1' WHERE id_session = 21"
        );

        DB::connection(self::FORMER_APP_DATABASE)->update(
            "UPDATE jeux SET  statut_jeu = '4' WHERE statut_jeu = 'awarded'"
        );
        DB::connection(self::FORMER_APP_DATABASE)->update(
            "UPDATE jeux SET statut_jeu = '3' WHERE statut_jeu = 'nominated'"
        );
        DB::connection(self::FORMER_APP_DATABASE)->update(
            "UPDATE jeux SET statut_jeu = '2' WHERE statut_jeu = 'qualified'"
        );
        DB::connection(self::FORMER_APP_DATABASE)->update(
            "UPDATE jeux SET statut_jeu = '1' WHERE statut_jeu = 'registered'"
        );
        DB::connection(self::FORMER_APP_DATABASE)->update(
            "UPDATE jeux SET statut_jeu = '1' WHERE statut_jeu = 'disqualified'"
        );
        DB::connection(self::FORMER_APP_DATABASE)->update(
            "UPDATE jeux SET statut_jeu = '0' WHERE statut_jeu = 'deleted'"
        );

        Schema::connection(self::FORMER_APP_DATABASE)->table('jeux', function (Blueprint $table) {
            $table->smallInteger('statut_jeu')->comment('0: supprimé; 1: inscrit; 2: qualifié; 3: nominé; 4: vainqueur')->nullable()->change();
        });
    }
}
