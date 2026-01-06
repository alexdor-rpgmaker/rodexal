<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AddUniqueTupleInLusTable extends Migration
{
    const FORMER_APP_DATABASE = 'former_app_database';

    public function up(): void
    {
        Schema::connection(self::FORMER_APP_DATABASE)->table('lus', function (Blueprint $table) {
            $table->renameColumn('id_visiteur', 'id_membre');
        });

        DB::connection(self::FORMER_APP_DATABASE)->statement(
            "ALTER TABLE lus ADD UNIQUE KEY uniq_id_sujet_id_membre (id_sujet, id_membre)"
        );
    }

    public function down(): void
    {
        DB::connection(self::FORMER_APP_DATABASE)->statement(
            'ALTER TABLE lus DROP INDEX uniq_id_sujet_id_membre'
        );

        Schema::connection(self::FORMER_APP_DATABASE)->table('lus', function (Blueprint $table) {
            $table->renameColumn('id_membre', 'id_visiteur');
        });
    }
}
