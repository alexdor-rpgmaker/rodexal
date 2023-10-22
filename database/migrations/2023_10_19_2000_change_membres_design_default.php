<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeMembresDesignDefault extends Migration
{
    const FORMER_APP_DATABASE = 'former_app_database';

    public function up(): void
    {
        Schema::connection(self::FORMER_APP_DATABASE)->table('membres', function (Blueprint $table) {
            $table->string('design')->default(NULL)->comment('')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::connection(self::FORMER_APP_DATABASE)->table('membres', function (Blueprint $table) {
            $table->string('design')->default('5')->comment('')->nullable()->change();
        });
    }
}
