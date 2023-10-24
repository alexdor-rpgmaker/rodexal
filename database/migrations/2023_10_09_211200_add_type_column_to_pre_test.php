<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTypeColumnToPreTest extends Migration
{
    public function up(): void
    {
        Schema::table('pre_tests', function (Blueprint $table) {
            $table->string('type')->nullable(false)->default('qcm');
        });
    }

    public function down(): void
    {
        Schema::table('pre_tests', function (Blueprint $table) {
            $table->dropColumn('type');
        });
    }
}
