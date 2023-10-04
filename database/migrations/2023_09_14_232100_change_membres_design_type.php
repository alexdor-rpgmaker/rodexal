<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ChangeMembresDesignType extends Migration
{
    const FORMER_APP_DATABASE = 'former_app_database';

    public function up(): void
    {
        Schema::connection(self::FORMER_APP_DATABASE)->table('membres', function (Blueprint $table) {
            $table->string('design')->comment('')->nullable()->change();
        });

        DB::connection(self::FORMER_APP_DATABASE)->update(
            "UPDATE membres SET design = '2011-lifaen' WHERE CAST(design AS NCHAR) = '0'"
        );

        DB::connection(self::FORMER_APP_DATABASE)->update(
            "UPDATE membres SET design = '2008-walina-khoryl' WHERE CAST(design AS NCHAR) = '1'"
        );

        DB::connection(self::FORMER_APP_DATABASE)->update(
            "UPDATE membres SET design = '2005-papillon' WHERE CAST(design AS NCHAR) = '2'"
        );

        DB::connection(self::FORMER_APP_DATABASE)->update(
            "UPDATE membres SET design = '2001-booskaboo' WHERE CAST(design AS NCHAR) = '3'"
        );

        DB::connection(self::FORMER_APP_DATABASE)->update(
            "UPDATE membres SET design = 'rpgmaker2000' WHERE CAST(design AS NCHAR) = '4'"
        );

        DB::connection(self::FORMER_APP_DATABASE)->update(
            "UPDATE membres SET design = '2013-alexre' WHERE CAST(design AS NCHAR) = '5'"
        );
    }

    public function down(): void
    {
        DB::connection(self::FORMER_APP_DATABASE)->update(
            "UPDATE membres SET design = '0' WHERE design = '2011-lifaen'"
        );

        DB::connection(self::FORMER_APP_DATABASE)->update(
            "UPDATE membres SET design = '1' WHERE design = '2008-walina-khoryl'"
        );

        DB::connection(self::FORMER_APP_DATABASE)->update(
            "UPDATE membres SET design = '2' WHERE design = '2005-papillon'"
        );

        DB::connection(self::FORMER_APP_DATABASE)->update(
            "UPDATE membres SET design = '3' WHERE design = '2001-booskaboo'"
        );

        DB::connection(self::FORMER_APP_DATABASE)->update(
            "UPDATE membres SET design = '4' WHERE design = 'rpgmaker2000'"
        );

        DB::connection(self::FORMER_APP_DATABASE)->update(
            "UPDATE membres SET design = '5' WHERE design = '2013-alexre'"
        );

        Schema::connection(self::FORMER_APP_DATABASE)->table('membres', function (Blueprint $table) {
            $table->smallInteger('design')->nullable(false)->default(5)->comment('0: Lifaen; 1: Walina & Khoryl; 2: Papillon; 3: Booskaboo; 4: RPG Maker 2000; 5: AlexRE')->change();
        });
    }
}
