<?php

namespace Tests\Unit;

use App\User;
use Tests\TestCase;
use App\Http\Services\UserService;
use Illuminate\Validation\ValidationException;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserServiceTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test UserService: Run the test with vendor/bin/phpunit in attached shell
     * 
     * - Name: 
     *      -> Required
     *      -> Max length: 255
     * - email
     *      -> Required
     *      -> Email
     *      -> Max length: 255
     *      -> Unique
     * - Password
     *      -> Required (only when creating users)
     *      -> Confirmed
     *      -> Max length: 255
     * - Roles
     *      -> Have to exist in Role table
     * 
     */

    public function test_Required_fields()
    {
        try {
            $userService = new UserService();
            $data['name'] = "";
            $userService->validateUser($data);
            $this->assertFalse(true, "Validation should have failed");

        } catch (ValidationException $e) {
            $this->assertTrue($e->validator->fails());
            $errors = $e->validator->errors()->keys();
            $this->assertTrue(in_array("name", $errors));
            $this->assertTrue(in_array("email", $errors));
            $this->assertTrue(in_array("password", $errors));
        }
    }

    public function test_Max_Length()
    {
        try {
            $userService = new UserService();
            $invalidString = "1234567890";
            $invalidString = str_repeat($invalidString, 26);
            $data['name'] = $invalidString;
            $data['email'] = $invalidString;
            $data['password'] = $invalidString;
            $data['password_confirmation'] = $invalidString;
            $userService->validateUser($data);
            $this->assertFalse(true, "Validation should have failed");

        } catch (ValidationException $e) {
            $this->assertTrue($e->validator->fails());
            $errors = $e->validator->errors()->keys();
            $this->assertTrue(in_array("name", $errors));
            $this->assertTrue(in_array("email", $errors));
            $this->assertTrue(in_array("password", $errors));
        }
    }

    public function test_email_invalid_format()
    {
        try {
            $userService = new UserService();
            $data['name'] = "name";
            $data['password'] = "password";
            $data['password_confirmation'] = "password";
            $data["email"] = "email";
            $userService->validateUser($data);
            $this->assertFalse(true, "Validation should have failed");

        } catch (ValidationException $e) {
            $this->assertTrue($e->validator->fails());
            $errors = $e->validator->errors()->keys();
            $this->assertTrue(count($errors) == 1 && in_array("email", $errors));
        }
    }

    public function test_email_valid_format()
    {
        try {
            $userService = new UserService();
            $data['name'] = "name";
            $data['password'] = "password";
            $data['password_confirmation'] = "password";
            $data["email"] = "email@email.com";
            $userService->validateUser($data);
            $this->assertTrue(true);

        } catch (ValidationException $e) {
            $this->assertTrue(false, "Validation should have not failed");
        }
    }

    public function test_email_unique_when_one_already_exists()
    {
        try {
            $userService = new UserService();
            $data['name'] = "name";
            $data['password'] = "password";
            $data["email"] = "email@email.com";
            $user = factory(User::class)->create($data);

            $data["email"] = "email@email.com";
            $data['password_confirmation'] = "password";
            $userService->validateUser($data);
            $this->assertFalse(true, "Validation should have failed");

        } catch (ValidationException $e) {
            $this->assertTrue($e->validator->fails());
            $errors = $e->validator->errors()->keys();
            $this->assertTrue(count($errors) == 1 && in_array("email", $errors));
        }
    }

    public function test_email_unique_but_oneself()
    {
        try {
            $userService = new UserService();
            $data['name'] = "name";
            $data["email"] = "email@email.com";
            $user = factory(User::class)->create($data);

            $data["email"] = "email@email.com";
            $userService->validateUser($data, $user->id);
            $this->assertTrue(true);

        } catch (ValidationException $e) {
            $this->assertTrue(false, "Validation should have worked");
        }
    }

    public function test_password_not_required_when_updating()
    {
        try {
            $user = factory(User::class)->create();

            $userService = new UserService();
            $data['name'] = "name";
            $data["email"] = "email@email.com";
            $userService->validateUser($data, $user->id);
            $this->assertTrue(true);

        } catch (ValidationException $e) {
            $this->assertTrue(false, "Validation should have not failed");
        }
    }

    public function test_password_confirmation()
    {
        try {
            $userService = new UserService();
            $data['name'] = "name";
            $data["email"] = "email@email.com";
            $data['password'] = "password";
            $data['password_confirmation'] = "different password";
            $userService->validateUser($data);
            $this->assertTrue(false, "Validation should have failed");

        } catch (ValidationException $e) {
            $this->assertTrue($e->validator->fails());
            $errors = $e->validator->errors()->keys();
            $this->assertTrue(count($errors) == 1 && in_array("password", $errors));
        }
    }
}
