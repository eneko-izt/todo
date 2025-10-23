<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class HelpersTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test the is_admin() helper function.
     *
     * This test checks if the is_admin() function correctly identifies
     * whether the currently authenticated user has the 'admin' role.
     * 
     * - A user that is both admin and user is recognized as admin
     * - A user that is only a user is not recognized as admin
     * - A user with no roles is not recognized as admin
     */
    public function test_Is_Admin()
    {
        $roleAdmin = factory(\App\Role::class)->create(['name' => 'admin']);
        $roleUser = factory(\App\Role::class)->create(['name' => 'user']);

        // Check that a user that is both admin and user is recognized as admin
        $user = $this->createUserWithRoles([$roleAdmin->id, $roleUser->id]);
        $this->actingAs($user);
        $this->assertTrue(is_admin());

        // Check that a user that is only a user is not recognized as admin
        $user = $this->createUserWithRoles([$roleUser->id]);
        $this->actingAs($user);
        $this->assertFalse(is_admin());

        // Check that a user with no roles is not recognized as admin
        $userNoRoles = $this->createUserWithRoles([]);
        $this->actingAs($userNoRoles);
        $this->assertFalse(is_admin());
    }

    private function createUserWithRoles($roleIds)
    {
        $user = factory(\App\User::class)->create();
        $user->roles()->attach($roleIds);
        return $user;
    }
}
