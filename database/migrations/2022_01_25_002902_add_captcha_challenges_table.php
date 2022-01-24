<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCaptchaChallengesTable extends Migration
{
    const FORMER_APP_DATABASE = 'former_app_database';

    public function up()
    {
        Schema::connection(self::FORMER_APP_DATABASE)->create('captcha_challenges', function (Blueprint $table) {
            $table->uuid('id')->nullable(false)->unique();
            $table->smallInteger('resultat')->nullable(false);
            $table->dateTime('date_generation')->nullable(false);
        });
    }

    public function down()
    {
        Schema::connection(self::FORMER_APP_DATABASE)->dropIfExists('captcha_challenges');
    }
}
