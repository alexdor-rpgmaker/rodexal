<?php

namespace Tests\Browser;

use Tests\DuskTestCase;

abstract class BrowserTestCase extends DuskTestCase
{
    private static $databasesRefreshed = false;

    protected function setUp(): void
    {
        parent::setUp();

        if (!self::$databasesRefreshed) {
            $this->artisan('migrate:refresh');
            self::$databasesRefreshed = true;
        }
    }
}
