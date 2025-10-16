<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Mail\TaskSharedMail;
use App\Classes\Languages;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\File;

class LanguageTest extends TestCase
{
    use RefreshDatabase;

    /*
     * Run the test with vendor/bin/phpunit in attached shell
     */

    /**
     * @dataProvider localeProvider
     */
    public function test_it_has_translation_files_for_all_locales($locale)
    {
        $path = resource_path("lang/{$locale}/auth.php");
        $this->assertFileExists($path, "Missing auth translation file for locale: {$locale}");
        $path = resource_path("lang/{$locale}/pagination.php");
        $this->assertFileExists($path, "Missing pagination translation file for locale: {$locale}");
        $path = resource_path("lang/{$locale}/passwords.php");
        $this->assertFileExists($path, "Missing passwords translation file for locale: {$locale}");
        $path = resource_path("lang/{$locale}/validation.php");
        $this->assertFileExists($path, "Missing validation translation file for locale: {$locale}");
        $path = resource_path("lang/{$locale}.json");
        $this->assertFileExists($path, "Missing JSON translation file for locale: {$locale}");
    }

    /**
     * @dataProvider localeProvider
     */
    public function test_it_loads_translations_correctly_for_each_locale($locale)
    {
        app()->setLocale($locale);
        $json = $this->loadJsonTranslations($locale);
        $this->assertIsArray($json, "Translations for locale {$locale} could not be loaded as array");
        $this->assertNotEmpty($json, "Translations for locale {$locale} are empty");
        $translation = __('Home');
        $this->assertIsString($translation, "Translation for locale {$locale} is not a string");
        if ($locale === 'en') {
            // In English, the translation is usually the same as the key.
            $this->assertEquals('Home', $translation, "Translation for locale {$locale} should be 'Home'");
        }
        else {
        // In other languages, the translation should differ from the key.
        $this->assertNotEquals('Home', $translation, "Translation for locale {$locale} is not translated");
        }
    }

    /**
     * @dataProvider localeProvider
     */
    public function test_task_share_email_messages($locale)
    {
        $column = factory(\App\Column::class)->create();
        $userOwner = factory(\App\User::class)->create();
        $task = factory(\App\Task::class)->create(['user_id' => $userOwner->id, 'column_id' => $column->id]);

        $user = factory(\App\User::class)->create();
        $this->actingAs($user)->get(route('home'));

        $user->language = $locale;
        $user->save();

        $json = $this->loadJsonTranslations($locale);

        $taskSharedMail = new TaskSharedMail($user, $task);
        $taskSharedMail->build();
        $this->assertEquals($taskSharedMail->locale, $locale);
        $this->assertEquals($taskSharedMail->subject, $json['Task Shared']);
        $this->assertStringContainsString($json['Thanks,'], $taskSharedMail->render());
    }

    public function localeProvider(): array
    {
        $locales = \App\Classes\Languages::getAll();

        $provider = [];
        foreach ($locales as $key => $name) {
            // Use the key (locale code) as both the dataset name and the parameter
            $provider[$key] = [$key];
        }

        return $provider;
    }

    protected function loadJsonTranslations(string $locale) : array
    {
        $path = resource_path("lang/{$locale}.json");
        if (! File::exists($path)) {
            return [];
        }
        $content = File::get($path);
        $parsed = json_decode($content, true);
        if (! is_array($parsed)) {
            return [];
        }
        // keys in JSON are usually the source strings or keys directly.
        return $parsed;
    }
}