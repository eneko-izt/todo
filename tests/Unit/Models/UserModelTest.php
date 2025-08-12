<?php

namespace Tests\Unit;

use App\User;
use App\Role;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Collection;
use Illuminate\Notifications\Notifiable;
use PHPUnit\Framework\TestCase;

class UserModelTest extends TestCase
{
    public function test_it_has_correct_fillable_attributes()
    {
        $user = new User();

        $this->assertEquals(
            ['name', 'active', 'email', 'password'],
            $user->getFillable()
        );
    }

    public function test_it_uses_notifiable_trait()
    {
        $this->assertContains(Notifiable::class, class_uses(User::class));
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
}
