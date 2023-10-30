<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTemplateColumnToSerieTests extends Migration
{
    const FORMER_APP_DATABASE = 'former_app_database';

    public function up(): void
    {
        Schema::connection(self::FORMER_APP_DATABASE)->table('series_tests', function (Blueprint $table) {
            $table->string('template')->nullable();
        });
    }

    public function down(): void
    {
        Schema::connection(self::FORMER_APP_DATABASE)->table('series_tests', function (Blueprint $table) {
            $table->dropColumn('template');
        });
    }
}
