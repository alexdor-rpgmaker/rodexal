<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Support\Facades\Log;

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
        Log::info( "--- Refreshing database ---");
        $this->artisan('migrate:refresh');
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
