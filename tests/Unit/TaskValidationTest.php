<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Http\Services\TaskService;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TaskValidationTest extends TestCase
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


    public function test_Create_Empty_Task()
    {
        $taskService = new TaskService();

        // Not existing column id so it must fail validation
        $task = $this->lastTask();
        $nonExistingColumnId = $task ? $task->id + 1 : 1;
        $data = array(
            'text' => null,
            'order' => null,
            'column_id' => $nonExistingColumnId,
            'tags' => [],
            'user_id' => null,
            'active' => 1
        );

        $validator = $taskService->createValidator($data);
        $err = $validator->fails() ? $validator->errors()->keys() : [];

        $response = count($err) == 4
            && in_array("text", $err) && in_array("order", $err)
            && in_array('column_id', $err) && in_array('user_id', $err);

        $this->assertTrue($response);
    }

    public function test_Cannot_Update_Empty_Task()
    {
        $taskService = new TaskService();
        $this->fillDatabase();

        $task = $this->lastTask();
        $taskId = $task->id;
        $data = array(
            'text' => null,
            'order' => null,
            'column_id' => null,
            'tags' => [],
            'user_id' => null,
            'active' => 1
        );

        $validator = $taskService->updateValidator($data, $task);
        $err = $validator->fails() ? $validator->errors()->keys() : [];

        $response = count($err) == 4
            && in_array("text", $err) && in_array("order", $err)
            && in_array("column_id", $err) && in_array('user_id', $err);

        $this->assertTrue($response);
    }

    public function test_Text()
    {
        $taskService = new TaskService();
        $this->fillDatabase();
        $column = $this->lastColumn();

        $data = $this->fillCreateRequestData();
        $columnId = $column->id;

        // Check empty string is invalid
        $data['text'] = "";

        $validator = $taskService->createValidator($data);
        $err = $validator->fails() ? $validator->errors()->keys() : [];
        $response = count($err) == 1 && in_array("text", $err);
        $this->assertTrue($response);

        // Check length > 255 is invalid
        $invalidString = "1234567890";
        $invalidString = str_repeat($invalidString, 26);
        $data['text'] = $invalidString;

        $validator = $taskService->createValidator($data);
        $err = $validator->fails() ? $validator->errors()->keys() : [];
        $response = count($err) == 1 && in_array("text", $err);
        $this->assertTrue($response);

        // Check a valid string
        $data['text'] = "abcd";
        $validator = $taskService->createValidator($data);
        $err = $validator->fails() ? $validator->errors()->keys() : [];
        $response = count($err) == 0;
        $this->assertTrue($response);
    }

    public function test_Order()
    {
        $taskService = new TaskService();
        $this->fillDatabase();
        $column = $this->lastColumn();

        $data = $this->fillCreateRequestData();
        $columnId = $column->id;

        // Check order must be numeric
        $data['order'] = "abcd";
        $validator = $taskService->createValidator($data);
        $err = $validator->fails() ? $validator->errors()->keys() : [];
        $response = count($err) == 1 && in_array("order", $err);
        $this->assertTrue($response);

        // Check order cannot be negative
        $data['order'] = -1;
        $validator = $taskService->createValidator($data);
        $err = $validator->fails() ? $validator->errors()->keys() : [];
        $response = count($err) == 1 && in_array("order", $err);
        $this->assertTrue($response);

        // Check order cannot be greater than 100
        $data['order'] = 101;
        $validator = $taskService->createValidator($data);
        $err = $validator->fails() ? $validator->errors()->keys() : [];
        $response = count($err) == 1 && in_array("order", $err);
        $this->assertTrue($response);

        // Check valid order
        $data['order'] = 1;
        $validator = $taskService->createValidator($data);
        $err = $validator->fails() ? $validator->errors()->keys() : [];
        $response = count($err) == 0;
        $this->assertTrue($response);
    }

    public function test_Column()
    {
        $taskService = new TaskService();
        $this->fillDatabase();

        $data = $this->fillCreateRequestData();

        // Check column cannot be null
        $data['column_id'] = null;
        $validator = $taskService->createValidator($data);
        $err = $validator->fails() ? $validator->errors()->keys() : [];
        $response = count($err) == 1 && in_array("column_id", $err);
        $this->assertTrue($response);

        // Check column must exist in table
        $nonExistingColumnId = $this->lastColumn()->id + 1;
        $data = $this->fillCreateRequestData();
        $data['column_id'] = $nonExistingColumnId;
        $validator = $taskService->createValidator($data);
        $err = $validator->fails() ? $validator->errors()->keys() : [];
        $response = count($err) == 1 && in_array("column_id", $err);
        $this->assertTrue($response);
    }

    public function test_Tags()
    {
        $taskService = new TaskService();
        $this->fillDatabase();

        $data = $this->fillCreateRequestData();
        $columnId = $this->lastColumn()->id;

        // Check tags validation succeeds when tags not provided
        $data['tags'] = [];
        $validator = $taskService->createValidator($data);
        $err = $validator->fails() ? $validator->errors()->keys() : [];
        $response = count($err) == 0;
        $this->assertTrue($response);

        // Check tags validation succeeds with an existing tag
        $data['tags'] = [$this->lastTag()->id];
        $validator = $taskService->createValidator($data);
        $err = $validator->fails() ? $validator->errors()->keys() : [];
        $response = count($err) == 0;
        $this->assertTrue($response);

        // Check tags validation fails with a not existing tag id
        $data['tags'] = [$this->lastTag()->id + 1];
        $validator = $taskService->createValidator($data);
        $err = $validator->fails() ? $validator->errors()->keys() : [];
        $response = count($err) == 1 && in_array("tags", $err);
        $this->assertTrue($response);
    }

    public function test_User()
    {
        $taskService = new TaskService();
        $this->fillDatabase();

        $data = $this->fillCreateRequestData();
        $columnId = $this->lastColumn()->id;

        // Check validation succeeds for existing user
        $data['user_id'] = $this->lastUser()->id;
        $validator = $taskService->createValidator($data);
        $err = $validator->fails() ? $validator->errors()->keys() : [];
        $response = count($err) == 0;
        $this->assertTrue($response);

        // create a new user model instance BUT do not save it in the database!
        // validation should fail
        $user = factory(\App\User::class)->make(['active' => 1]);
        $this->actingAs($user);
        $data['user_id'] = $user->id;
        $validator = $taskService->createValidator($data);
        $err = $validator->fails() ? $validator->errors()->keys() : [];
        $response = count($err) == 1 && in_array("user_id", $err);
        $this->assertTrue($response);
    }

    private function fillDatabase()
    {
        $role = factory(\App\Role::class)->create(['name' => 'user']);
        $user = factory(\App\User::class)->create();
        $user->roles()->attach($role);
        $column = factory(\App\Column::class)->create();
        $tag = factory(\App\Tag::class)->create();
        $task = factory(\App\Task::class)->create(['user_id' => $user->id, 'column_id' => $column->id]);
    }

    private function fillCreateRequestData()
    {
        $data = array(
            'text' => 'abcdef',
            'order' => 1,
            'column_id' => $this->lastColumn()->id,
            'tags' => [$this->lastTag()->id],
            'user_id' => $this->lastUser()->id,
            'active' => 1
        );

        return $data;
    }

    private function lastColumn()
    {
        return \App\Column::all()->last();
    }

    private function lastTag()
    {
        return \App\Tag::all()->last();
    }

    private function lastUser()
    {
        return \App\User::all()->last();
    }

    private function lastTask()
    {
        return \App\Task::all()->last();
    }
}
