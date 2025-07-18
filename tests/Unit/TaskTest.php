<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use ReflectionClass;

class TaskTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test Task validation: Run the test with vendor/bin/phpunit in attached shell
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
        $roleAdmin = factory(\App\Role::class)->create(['name' => 'admin']);

        $column = factory(\App\Column::class)->create([
            'active' => true,
            'deleted_at' => null
        ]);

        $this->checkEmptyTask();
    }

    private $transformer;
    private $getItemList;

    private function setupControllerValidation()
    {
        $this->transformer = new \App\Http\Controllers\TasksController();

        $reflection = new ReflectionClass(get_class($this->transformer));

        $this->getItemList = $reflection->getMethod('createValidator');

        $this->getItemList->setAccessible(true);
    }

    private function checkEmptyTask()
    {
        $this->setupControllerValidation();

        $id = '';
        $data = [];

        $data['text' . $id] = null;
        $data['order' . $id] = null;
        $data['column_id' . $id] = null;
        $data['tags' . $id] = [];
        $data['user_id' . $id] = null; //auth()->id();

        $data['active'] = 1;

        $err = [];
        $validator = $this->getItemList->invokeArgs($this->transformer, [$data]);
        if ($validator->fails())
        {
            $err = $validator->errors()->keys();
        }

        $res = count($err) == 4 
            && array_key_exists('text' . $id, $err) && array_key_exists('order' . $id, $err)
            && array_key_exists('column_id' . $id, $err) && array_key_exists('user_id' . $id, $err);

        $this->assertTrue($res);
    }
}
