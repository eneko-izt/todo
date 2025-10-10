<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Mail\TaskSharedMail;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LanguageTest extends TestCase
{
    use RefreshDatabase;

    /*
     * Run the test with vendor/bin/phpunit in attached shell
     */

    public function test_auth_messages()
    {
        $userEn = factory(\App\User::class)->create(['language' => 'en']);
        $userEu = factory(\App\User::class)->create(['language' => 'eu']);
        $userFr = factory(\App\User::class)->create(['language' => 'fr']);

        $this->actingAs($userEn)->get(route('home'));
        $this->assertEquals(__('auth.failed'), 'These credentials do not match our records.');

        $this->actingAs($userEu)->get(route('home'));
        $this->assertEquals(__('auth.failed'), 'Kredentzial hauek ez datoz bat gure erregistroekin.');

        // When a language is not available, it should fallback to Euskera as fallback language
        $this->actingAs($userFr)->get(route('home'));
        $this->assertEquals(__('auth.failed'), 'Kredentzial hauek ez datoz bat gure erregistroekin.');
    }

    public function test_json_messages()
    {
        $userEn = factory(\App\User::class)->create(['language' => 'en']);
        $userEu = factory(\App\User::class)->create(['language' => 'eu']);
        $userFr = factory(\App\User::class)->create(['language' => 'fr']);

        $this->actingAs($userEn)->get(route('home'));
        $this->assertEquals(__('Task Manager'), 'Task Manager');

        $this->actingAs($userEu)->get(route('home'));
        $this->assertEquals(__('Task Manager'), 'Ataza Kudeatzailea');

        // When a language is not available, it should fallback to Euskera as fallback language...
        // except for json files IN LARAVEL 6!
        $this->actingAs($userFr)->get(route('home'));
        $this->assertEquals(__('Task Manager'), 'Task Manager');
    }

    public function test_task_share_email_messages()
    {
        $column = factory(\App\Column::class)->create();
        $userOwner = factory(\App\User::class)->create();
        $task = factory(\App\Task::class)->create(['user_id' => $userOwner->id, 'column_id' => $column->id]);

        $userEn = factory(\App\User::class)->create(['language' => 'en']);
        $userEu = factory(\App\User::class)->create(['language' => 'eu']);
        $userFr = factory(\App\User::class)->create(['language' => 'fr']);

        $this->actingAs($userEn)->get(route('home'));
        $taskSharedMail = new TaskSharedMail($userEn, $task);
        $taskSharedMail->build();
        $this->assertEquals($taskSharedMail->locale, 'en');
        $this->assertEquals($taskSharedMail->subject, 'Task Shared');
        $this->assertStringContainsString('You have been granted access to task', $taskSharedMail->render());

        $this->actingAs($userEu)->get(route('home'));
        $taskSharedMail = new TaskSharedMail($userEu, $task);
        $taskSharedMail->build();
        $this->assertEquals($taskSharedMail->locale, 'eu');
        $this->assertEquals($taskSharedMail->subject, 'Ataza Partekatua');
        $this->assertStringContainsString('Ataza honetara sarbidea eman zaizu', $taskSharedMail->render());

        // When a language is not available, it should fallback to Euskera as fallback language...
        // except for json files IN LARAVEL 6!
        $this->actingAs($userFr)->get(route('home'));
        $taskSharedMail = new TaskSharedMail($userFr, $task);
        $taskSharedMail->build();
        $this->assertEquals($taskSharedMail->locale, 'fr');
        $this->assertEquals($taskSharedMail->subject, 'Task Shared');
        $this->assertStringContainsString('You have been granted access to task', $taskSharedMail->render());
    }
}