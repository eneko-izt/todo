<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RoleModelTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test Role model: Run the test with vendor/bin/phpunit in attached shell
     *
     * Requirements:
     *      -> Role name must be unique
     *      -> Role name must not be null
     *      -> Roles can be assigned to multiple users and Pivot table Soft delete functionality must work correctly
     *      -> User Roles should be retrievable with and without soft deletes
     *      -> Role can be removed from users
     *      -> Roles should be able to be restored after soft delete
     * 
     */

    private const numberOfUsers = 10;
    private const numberOfUsersWithRoles = 7;


    public function testRoleNameUnique()
    {
        $role = factory(\App\Role::class)->create(['name' => 'testRole']);
        $this->expectException(\Illuminate\Database\QueryException::class);
        factory(\App\Role::class)->create(['name' => 'testRole']);
    }

    public function testRoleNameNotNull()
    {
        $this->expectException(\Illuminate\Database\QueryException::class);
        factory(\App\Role::class)->create(['name' => null]);
    }

    public function testBelongsToManyUsers()
    {
        $role = factory(\App\Role::class)->create(['name' => 'testRole']);
        $users = factory(\App\User::class, self::numberOfUsers)->create();

        $usersWithRole = $users->take(self::numberOfUsersWithRoles);
        $usersWithDeletedRole = $users->skip(self::numberOfUsersWithRoles);

        $usersWithRole->each(function ($user) use ($role) {
            $user->roles()->attach($role, ['created_at' => now(), 'updated_at' => now(), 'deleted_at' => null]);
        });

        $usersWithDeletedRole->each(function ($user) use ($role) {
            $user->roles()->attach($role, ['created_at' => now(), 'updated_at' => now(), 'deleted_at' => now()]);
        });

        $this->assertEqualsCanonicalizing(
            $usersWithRole->pluck('id')->toArray(),
            $role->users()->get()->pluck("id")->toArray()
        );

        $this->assertEqualsCanonicalizing(
            $usersWithDeletedRole->pluck('id')->toArray(),
            $role->usersWithTrashed()->whereNotNull('role_user.deleted_at')->get()->pluck("id")->toArray()
        );
    }
}
