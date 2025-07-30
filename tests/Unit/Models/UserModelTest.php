<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserModelTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test Task validation: Run the test with vendor/bin/phpunit in attached shell
     * 
     * - User id
     *      -> Has to exist in user table
     *      -> Only user who created the task can update it
     * 
     */


    public function testSomething()
    {
        $this->assertTrue(true);
    }
}
