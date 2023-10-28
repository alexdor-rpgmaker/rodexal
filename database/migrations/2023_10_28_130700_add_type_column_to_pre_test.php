<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AddTypeColumnToPreTest extends Migration
{
    public function up(): void
    {
        Schema::table('pre_tests', function (Blueprint $table) {
            $table->string('final_thought')->nullable(false)->change();
            $table->string('type')->nullable(false)->default('qcm');
        });

        DB::table('pre_tests')
            ->where('final_thought', '1')
            ->update(['final_thought' => 'ok']);
        DB::table('pre_tests')
            ->where('final_thought', '0')
            ->update(['final_thought' => 'not-ok']);
    }

    public function down(): void
    {
        DB::table('pre_tests')
            ->where('final_thought', 'ok')
            ->update(['final_thought' => '1']);
        DB::table('pre_tests')
            ->where('final_thought', 'not-ok')
            ->update(['final_thought' => '0']);

        Schema::table('pre_tests', function (Blueprint $table) {
            $table->dropColumn('type');
            $table->boolean('final_thought')->nullable(false)->change();
        });
    }
}
