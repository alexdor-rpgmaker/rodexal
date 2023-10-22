<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

abstract class FeatureTestCase extends TestCase
{
    protected static bool $databasesRefreshed = false;

    protected function setUp(): void
    {
        parent::setUp();

        if (!self::$databasesRefreshed) {
            $this->refreshDatabase();
            self::$databasesRefreshed = true;
        }
    }

    protected function refreshDatabase(): void
    {
        Log::info("--- Refreshing database ---");

        // artisan migrate:fresh does not drop & recreate the other databases, so we need to do it manually
        $formerAppDatabaseName = $_ENV['FORMER_APP_DB_DATABASE'];
        DB::statement("DROP DATABASE `{$formerAppDatabaseName}`");
        DB::statement("CREATE DATABASE `{$formerAppDatabaseName}`");
        $this->artisan('migrate:fresh');
    }

    protected static function refreshDatabaseOnNextSetup(): void
    {
        self::$databasesRefreshed = false;
    }

//    protected function assertDataIds($response, $ids): void
//    {
//        $responseBody = json_decode($response->getContent(), true);
//        $this->assertEquals($ids, Arr::pluck($responseBody['data'], 'id'));
//    }
}
