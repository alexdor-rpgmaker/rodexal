<?php

namespace Tests\Feature;

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
}
