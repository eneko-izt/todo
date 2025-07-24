<?php

namespace Tests\Unit;

use Tests\TestCase;
use ReflectionClass;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TaskTest extends TestCase
{
    use RefreshDatabase;


    private $transformer;
    private $getItemList;
    private $user;
    private $column;
    private $tag;
    private $task;

    /**
     * Test Task validation: Run the test with vendor/bin/phpunit in attached shell
     * 
     * - A Unique id is suffixed to request fields.
     *      -> When creating a task column_id is not suffixed (it is used as unique id!)
     *      -> When updating a task task id is used to suffix fields. So column_id can also be suffixed
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

    //TODO: test bat konprobaketa bat 
    //7 gauza ezberdin testatzen ari gara hemen, kodearen mantentze-lanak zailtzen du
    //Hobe da test bakoitza gauza bakar bat egiaztatzea, eta horrela mantentze-lanak errazagoak dira
    //php vendor/bin/phpunit  --testdox oso zaila da jarkitea zer ari garen testeatzen
    public function testExample()
    {
        $role = factory(\App\Role::class)->create(['name' => 'user']);
        $this->user = factory(\App\User::class)->create(['active' => 1]);
        $this->user->roles()->attach($role);
        $this->column = factory(\App\Column::class)->create(['active' => true, 'deleted_at' => null]);

        $this->tag = factory(\App\Tag::class)->create();

        $this->task = factory(\App\Task::class)->create([
            'user_id' => $this->user->id,
            'column_id' => $this->column->id,
            'active' => true,
            'deleted_at' => null
        ]);

        // The next 2 checks also verify suffix mechanism works
        $this->checkEmptyTaskCreate();
        $this->checkEmptyTaskUpdate();

        // check Text
        $this->checkText();

        // check Order
        $this->checkOrder();

        // check column id
        $this->checkColumn();

        // check tags
        $this->checkTags();

        // check user id
        $this->checkUser();
    }


    private function setupControllerValidation($method)
    {
        //TODO: hau ez dut oso ondo ulertzen, zertarako da?
        $this->transformer = new \App\Http\Controllers\TasksController();
        $reflection = new ReflectionClass(get_class($this->transformer));
        $this->getItemList = $reflection->getMethod($method);
        $this->getItemList->setAccessible(true);
    }

    private function checkEmptyTaskCreate()
    {
        $this->setupControllerValidation('createValidator');

        // When creating Column id is used to suffix fields with a unique identifier
        // Therefore itself cannot be suffixed
        $id = 111; // Not existing column so it should fail validation
        //TODO: harkodeatutako id-ak ez dira oso adierazgarriak, zergatik ez da erabiltzen $nonExistingColumnId? (Bilatu azken id, eta hurrengoa erabili, adibidez)
        $data = array(
            'text' . $id => null,
            'order' . $id => null,
            'column_id' => $id,
            'tags' . $id => [],
            'user_id' . $id => null,
            'active' => 1
        );

        $err = $this->invokeValidate([$data]);

        $res = count($err) == 4
            && in_array("text" . $id, $err) && in_array("order" . $id, $err)
            && in_array('column_id', $err) && in_array('user_id' . $id, $err);

        $this->assertTrue($res);
    }

    private function checkEmptyTaskUpdate()
    {
        $this->setupControllerValidation('updateValidator');

        // When updating Task id is used to suffix fields with a unique identifier
        // When updating a task column id cannot has also to be suffixed with task_id
        $id = $this->task->id;
        $data = array(
            'text' . $id => null,
            'order' . $id => null,
            'column_id' . $id => null,
            'tags' . $id => [],
            'user_id' . $id => null,
            'active' => 1
        );

        $err = $this->invokeValidate([$data, $this->task]);

        $res = count($err) == 4
            && in_array("text" . $id, $err) && in_array("order" . $id, $err)
            && in_array("column_id" . $id, $err) && in_array('user_id' . $id, $err);

        $this->assertTrue($res);
    }

    private function checkText()
    {
        $this->setupControllerValidation('createValidator');

        // When creating Column id is used to suffix fields with a unique identifier
        // Therefore itself cannot be suffixed
        $data = $this->fillCreateRequestData();
        $id = $this->column->id;

        // Check empty string is invalid
        $data['text' . $id] = "";
        $err = $this->invokeValidate([$data]);
        $res = count($err) == 1 && in_array("text" . $id, $err);
        $this->assertTrue($res);

        // Check length > 255 is invalid
        $invalidString = "1234567890";
        $invalidString = str_repeat($invalidString, 26);
        $data['text' . $id] = $invalidString;
        $err = $this->invokeValidate([$data]);
        $res = count($err) == 1 && in_array("text" . $id, $err);
        $this->assertTrue($res);

        // Check a valid string
        $data['text' . $id] = "abcd";
        $err = $this->invokeValidate([$data]);
        $res = count($err) == 0;
        $this->assertTrue($res);
    }

    private function checkOrder()
    {
        $this->setupControllerValidation('createValidator');

        // When creating Column id is used to suffix fields with a unique identifier
        // Therefore itself cannot be suffixed
        $data = $this->fillCreateRequestData();
        $id = $this->column->id;

        // Check order must be numeric
        $data['order' . $id] = "abcd";
        $err = $this->invokeValidate([$data]);
        //TODO: aldagaien izenak ez dira oso adierazgarriak, zer da $res? $response jarrita errazagoa da kodea ultertzea.
        $res = count($err) == 1 && in_array("order" . $id, $err);
        $this->assertTrue($res);

        // Check order cannot be negative
        $data['order' . $id] = -1;
        $err = $this->invokeValidate([$data]);
        $res = count($err) == 1 && in_array("order" . $id, $err);
        $this->assertTrue($res);

        // Check order cannot be greater than 100
        $data['order' . $id] = 101;
        $err = $this->invokeValidate([$data]);
        $res = count($err) == 1 && in_array("order" . $id, $err);
        $this->assertTrue($res);

        // Check valid order
        $data['order' . $id] = 1;
        $err = $this->invokeValidate([$data]);
        $res = count($err) == 0;
        $this->assertTrue($res);
    }

    private function checkColumn()
    {
        $this->setupControllerValidation('createValidator');

        // When creating Column id is used to suffix fields with a unique identifier
        // Therefore itself cannot be suffixed
        $columnId = $this->column->id;

        $data = $this->fillCreateRequestData();
        $id = $this->column->id;

        // Check column cannot be null
        $this->column->id = null;
        $data = $this->fillCreateRequestData();
        $err = $this->invokeValidate([$data]);
        $res = count($err) == 1;
        $this->assertTrue($res);

        // Check column must exist in table
        $this->column->id = 3662;
        $data = $this->fillCreateRequestData();
        $err = $this->invokeValidate([$data]);
        $res = count($err) == 1 && in_array("column_id", $err);
        $this->assertTrue($res);

        $this->column->id = $columnId;
    }

    private function checkTags()
    {
        $this->setupControllerValidation('createValidator');

        // When creating Column id is used to suffix fields with a unique identifier
        // Therefore itself cannot be suffixed
        $data = $this->fillCreateRequestData();
        $id = $this->column->id;

        // Check tags validation succeeds when tags not provided
        $data['tags' . $id] = [];
        $err = $this->invokeValidate([$data]);
        $res = count($err) == 0;
        $this->assertTrue($res);

        // Check tags validation succeeds with an existing tag
        $data['tags' . $id] = [$this->tag->id];
        $err = $this->invokeValidate([$data]);
        $res = count($err) == 0;
        $this->assertTrue($res);

        // Check tags validation fails with a not existing tag id
        $data['tags' . $id] = [5335];
        $err = $this->invokeValidate([$data]);
        $res = count($err) == 1 && in_array("tags" . $id, $err);
        $this->assertTrue($res);
    }

    private function checkUser()
    {
        $this->setupControllerValidation('createValidator');

        // When creating Column id is used to suffix fields with a unique identifier
        // Therefore itself cannot be suffixed
        $data = $this->fillCreateRequestData();
        $id = $this->column->id;

        // Check validation succeeds for existing user
        $data['user_id' . $id] = $this->user->id;
        $err = $this->invokeValidate([$data]);
        $res = count($err) == 0;
        $this->assertTrue($res);

        // create a new user model instance BUT do not save it in the database!
        $user = factory(\App\User::class)->make(['active' => 1]);
        $this->actingAs($user);
        $data['user_id' . $id] = $user->id;
        $err = $this->invokeValidate([$data]);
        $res = count($err) == 1 && in_array("user_id" . $id, $err);
        $this->assertTrue($res);
    }

    private function invokeValidate($params)
    {
        $err = [];
        $validator = $this->getItemList->invokeArgs($this->transformer, $params);
        if ($validator->fails()) {
            $err = $validator->errors()->keys();
        }
        return $err;
    }

    private function fillCreateRequestData()
    {
        $id = $this->column->id;
        $data = array(
            'text' . $id => 'abcdef',
            'order' . $id => 1,
            'column_id' => $id,
            'tags' . $id => [$this->tag->id],
            'user_id' . $id => $this->user->id,
            'active' => 1
        );

        return $data;
    }
}
