<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TaskTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test Task validation
     * 
     * - Text: 
     *      -> Required
     *      -> Max length: 255
     * - Order
     *      -> Required
     *      -> Has to be a numeric value
     *      -> Value has to be between 0 and 100
     * - Column_id
     *      -> Required
     *      -> Has to exist in column table
     * - Tags
     *      -> Have to exist in Tag table
     * - User id
     *      -> Has to exist in user table
     *      -> Only user who created the task can update it
     * 
     */
    public function testExample()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }
}
