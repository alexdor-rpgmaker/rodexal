<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AddNouveauPasseToMembres extends Migration
{
    const FORMER_APP_DATABASE = 'former_app_database';

    public function up(): void
    {
        Schema::connection(self::FORMER_APP_DATABASE)->table('membres', function (Blueprint $table) {
            $table->renameColumn('passe', 'vieux_passe');
            $table->renameColumn('confirmation', 'code_confirmation_mail');
            $table->string('nouveau_passe')->after('passe')->nullable();
        });
        Schema::connection(self::FORMER_APP_DATABASE)->table('mdp_remake', function (Blueprint $table) {
            $table->string('nb_verif')->change();
            $table->renameColumn('nb_verif', 'code_confirmation_reset_passe');
            $table->renameColumn('id_mb', 'id_membre');
            $table->dateTime('date_creation')->nullable();
        });

        DB::connection(self::FORMER_APP_DATABASE)->update(
            'UPDATE mdp_remake SET date_creation = FROM_UNIXTIME(timestamp)'
        );
    }

    public function down(): void
    {
        DB::connection(self::FORMER_APP_DATABASE)->update(
            'UPDATE mdp_remake SET code_confirmation_reset_passe = 0'
        );

        Schema::connection(self::FORMER_APP_DATABASE)->table('mdp_remake', function (Blueprint $table) {
            $table->dropColumn('date_creation');
            $table->renameColumn('id_membre', 'id_mb');
            $table->renameColumn('code_confirmation_reset_passe', 'nb_verif');
            $table->integer('code_confirmation_reset_passe')->change();
        });
        Schema::connection(self::FORMER_APP_DATABASE)->table('membres', function (Blueprint $table) {
            $table->dropColumn('nouveau_passe');
            $table->renameColumn('code_confirmation_mail', 'confirmation');
            $table->renameColumn('vieux_passe', 'passe');
        });
    }
}
