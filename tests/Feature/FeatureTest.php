<?php

namespace Tests\Feature;

use Illuminate\Support\Arr;
use Tests\TestCase;

abstract class FeatureTest extends TestCase
{
    private static $databasesRefreshed = false;

    protected function setUp(): void
    {
        parent::setUp();

        if (!self::$databasesRefreshed) {
            $this->resetDatabase();
            self::$databasesRefreshed = true;
        }
    }

    protected function resetDatabase(): void
    {
        $this->artisan('migrate:refresh');
    }

    protected function assertDataIds($response, $ids): void
    {
        $responseBody = json_decode($response->getContent(), true);
        $this->assertEquals($ids, Arr::pluck($responseBody['data'], 'id'));
    }
}
