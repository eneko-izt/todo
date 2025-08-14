<?php

namespace Tests\Unit;

use App\Role;
use App\Task;
use App\User;
use App\Column;
use Tests\TestCase;
use Illuminate\Support\Collection;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class UserModelTest extends TestCase
{
    use RefreshDatabase;
 
    public function test_it_has_correct_fillable_attributes()
    {
        $user = new User();

        $columnNames = ['name', 'active', 'email', 'password'];

        $arraysAreEqual = empty(array_diff($columnNames, $user->getFillable()))
            && empty(array_diff($user->getFillable(), $columnNames));
        $this->assertTrue($arraysAreEqual);
    }

    public function test_it_uses_notifiable_trait()
    {
        $this->assertContains(Notifiable::class, class_uses(User::class));
    }

    public function test_tasks_relationship_is_has_many()
    {
        $user = new User();
        $relation = $user->tasks();

        $this->assertInstanceOf(HasMany::class, $relation);
        $this->assertEquals('user_id', $relation->getForeignKeyName());
        $this->assertEquals('id', $relation->getLocalKeyName());
    }

    public function test_roles_relationship_is_belongs_to_many()
    {
        $user = new User();
        $relation = $user->roles();

        $this->assertInstanceOf(BelongsToMany::class, $relation);
        $this->assertEquals('role_user', $relation->getTable());
    }

    public function test_has_role_name_returns_true_when_role_exists_and_not_deleted()
    {
        $role = new Role(['name' => 'admin', 'deleted_at' => null]);
        $role->pivot = (object)['deleted_at' => null];

        $user = new User();
        $user->setRelation('roles', new Collection([$role]));

        $this->assertTrue($user->hasRoleName('admin'));
    }

    public function test_has_role_name_returns_false_when_role_not_found()
    {
        $user = new User();
        $user->setRelation('roles', new Collection());

        $this->assertFalse($user->hasRoleName('admin'));
    }

    public function test_it_has_zero_task()
    {
        $user = factory(User::class)->create();
        $this->assertCount(0, $user->tasks);
    }

    public function test_it_has_one_task()
    {
        $user = factory(User::class)->create();
        $column = factory(Column::class)->create(['deleted_at' => null]);
        $task = factory(Task::class)->create(['column_id' => $column->id, 'user_id' => $user->id, 'deleted_at' => null]);
        $deletedTask = factory(Task::class)->create(['column_id' => $column->id, 'user_id' => $user->id, 'deleted_at' => now()]);

        $notUsedUser = factory(User::class)->create();
        $notUsedTask = factory(Task::class)->create(['column_id' => $column->id, 'user_id' => $notUsedUser->id, 'deleted_at' => null]);

        $this->assertTrue($user->tasks->contains($task));
        $this->assertCount(1, $user->tasks);
        $this->assertInstanceOf(Task::class, $user->tasks->first());
    }

    public function test_it_has_many_tasks()
    {
        $user = factory(User::class)->create();
        $column = factory(Column::class)->create(['deleted_at' => null]);
        $task1 = factory(Task::class)->create(['column_id' => $column->id, 'user_id' => $user->id, 'deleted_at' => null]);
        $task2 = factory(Task::class)->create(['column_id' => $column->id, 'user_id' => $user->id, 'deleted_at' => null]);
        $task3 = factory(Task::class)->create(['column_id' => $column->id, 'user_id' => $user->id, 'deleted_at' => null]);
        $deletedTask = factory(Task::class)->create(['column_id' => $column->id, 'user_id' => $user->id, 'deleted_at' => now()]);

        $notUsedUser = factory(User::class)->create();
        $notUsedTask = factory(Task::class)->create(['column_id' => $column->id, 'user_id' => $notUsedUser->id, 'deleted_at' => null]);

        $tasks = collect([$task1, $task2, $task3]);
        $arraysAreEqual = empty(array_diff($tasks->pluck('id')->toArray(), $user->tasks->pluck('id')->toArray()))
            && empty(array_diff($user->tasks->pluck('id')->toArray(), $tasks->pluck('id')->toArray()));
        $this->assertTrue($arraysAreEqual);
    }

    public function test_it_has_zero_roles()
    {
        $user = factory(User::class)->create();
        $this->assertCount(0, $user->roles);
    }

    public function test_it_has_one_role()
    {
        $user = factory(User::class)->create();
        $role = factory(Role::class)->create(['deleted_at' => null]);
        $deletedRole = factory(Role::class)->create(['deleted_at' => now()]);

        // Attach role to user
        $user->roles()->attach($role->id);
        $user->roles()->attach($deletedRole->id);

        $notUsedUser = factory(User::class)->create();
        $notUsedRole = factory(Role::class)->create(['deleted_at' => null]);

        // Attach unused role to unused user
        $notUsedUser->roles()->attach($notUsedRole->id);

        $this->assertTrue($user->roles->contains($role));
        $this->assertInstanceOf(Role::class, $user->roles->first());
    }

    public function test_it_has_many_roles()
    {
        $user = factory(User::class)->create();
        $role1 = factory(Role::class)->create(['deleted_at' => null]);
        $role2 = factory(Role::class)->create(['deleted_at' => null]);
        $role3 = factory(Role::class)->create(['deleted_at' => null]);

        // Attach role to user
        $user->roles()->attach($role1->id);
        $user->roles()->attach($role2->id);
        $user->roles()->attach($role3->id);

        $roles = collect([$role1, $role2, $role3]);
        $arraysAreEqual = empty(array_diff($roles->pluck('id')->toArray(), $user->roles->pluck('id')->toArray()))
            && empty(array_diff($user->roles->pluck('id')->toArray(), $roles->pluck('id')->toArray()));
        $this->assertTrue($arraysAreEqual);
    }
}
