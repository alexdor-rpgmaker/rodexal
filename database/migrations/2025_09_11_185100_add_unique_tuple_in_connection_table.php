<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class AddUniqueTupleInConnectionTable extends Migration
{
    const FORMER_APP_DATABASE = 'former_app_database';

    public function up(): void
    {
      DB::connection(self::FORMER_APP_DATABASE)->table('connexions')->truncate();

      DB::connection(self::FORMER_APP_DATABASE)->statement(
        "ALTER TABLE connexions ADD id_membre_safe INT AS (IFNULL(id_membre, 0)) STORED"
      );
      DB::connection(self::FORMER_APP_DATABASE)->statement(
        "ALTER TABLE connexions ADD UNIQUE KEY uniq_ip_membre (ip, id_membre_safe)"
      );
    }

    public function down(): void
    {
      DB::connection(self::FORMER_APP_DATABASE)->statement(
        'ALTER TABLE connexions DROP INDEX uniq_ip_membre, DROP COLUMN id_membre_safe'
      );
    }
}
