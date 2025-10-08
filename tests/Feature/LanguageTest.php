<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LanguageTest extends TestCase
{
    use RefreshDatabase;

    /*
     * Run the test with vendor/bin/phpunit in attached shell
     */

    public function test_User_Not_Owner_Cannot_View_Unshared_Task()
    {
        $userEn = factory(\App\User::class)->create(['language' => 'en']);
        $userEu = factory(\App\User::class)->create(['language' => 'eu']);

        $this->actingAs($userEn)->get(route('home'));
        $this->assertEquals(__('auth.failed'), 'These credentials do not match our records.');

        $this->actingAs($userEu)->get(route('home'));
        $this->assertEquals(__('auth.failed'), 'Kredentzial hauek ez datoz bat gure erregistroekin.');
    }
}