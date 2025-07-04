<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HelpersTest extends TestCase
{
    use RefreshDatabase;

    public function testIsAdmin()
    {
        $user = factory(\App\User::class)->create(['name' => 'userAdminUser']);
        $user = factory(\App\User::class)->create(['name' => 'userAdmin']);
        $user = factory(\App\User::class)->create(['name' => 'userUser']);
        $user = factory(\App\User::class)->create(['name' => 'userNothing']);

        $roleAdmin = factory(\App\Role::class)->create([
            'name' => 'admin'
        ]);

        $roleUser = factory(\App\Role::class)->create([
            'name' => 'user'
        ]);

        \App\User::where('name', 'userAdminUser')->first()->roles()->attach($roleAdmin->id);
        \App\User::where('name', 'userAdminUser')->first()->roles()->attach($roleUser->id);

        \App\User::where('name', 'userAdmin')->first()->roles()->attach($roleAdmin->id);

        \App\User::where('name', 'userUser')->first()->roles()->attach($roleUser->id);

        $this->actingAs(\App\User::where('name', 'userAdminUser')->first());
        $this->assertTrue(is_admin());

        $this->actingAs(\App\User::where('name', 'userAdmin')->first());
        $this->assertTrue(is_admin());

        $this->actingAs(\App\User::where('name', 'userUser')->first());
        $this->assertFalse(is_admin());

        $this->actingAs(\App\User::where('name', 'userNothing')->first());
        $this->assertFalse(is_admin());
    }
}
