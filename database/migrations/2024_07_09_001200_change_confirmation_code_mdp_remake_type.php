<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeConfirmationCodeMdpRemakeType extends Migration
{
    const FORMER_APP_DATABASE = 'former_app_database';

    public function up(): void
    {
        Schema::connection(self::FORMER_APP_DATABASE)->table('mdp_remake', function (Blueprint $table) {
            $table->string('code_confirmation_reset_passe')->change();
            $table->integer('timestamp')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::connection(self::FORMER_APP_DATABASE)->table('mdp_remake', function (Blueprint $table) {
            $table->integer('timestamp')->nullable(false)->change();
            $table->integer('code_confirmation_reset_passe')->change();
        });
    }
}
