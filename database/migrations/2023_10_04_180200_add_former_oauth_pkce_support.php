<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFormerOAuthPkceSupport extends Migration
{
    const FORMER_APP_DATABASE = 'former_app_database';

    public function up(): void
    {

        Schema::connection(self::FORMER_APP_DATABASE)->table('oauth_authorization_codes', function (Blueprint $table) {
            $table->string('code_challenge', 1000)->nullable();
            $table->string('code_challenge_method', 20)->nullable();
        });

    }

    public function down(): void
    {
        Schema::connection(self::FORMER_APP_DATABASE)->table('oauth_authorization_codes', function (Blueprint $table) {
            $table->dropColumn(['code_challenge', 'code_challenge_method']);
        });
    }
}
