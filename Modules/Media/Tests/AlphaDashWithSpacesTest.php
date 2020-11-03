<?php

namespace Modules\Media\Tests;

use Illuminate\Container\Container;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Translation\FileLoader;
use Illuminate\Translation\Translator;
use Illuminate\Validation\Factory as Validator;
use Modules\Media\Validators\AlphaDashWithSpaces;

final class AlphaDashWithSpacesTest extends MediaTestCase
{
    public function test_it_creates_instance_of_validator()
    {
        $obj = new AlphaDashWithSpaces();
        $this->assertInstanceOf(AlphaDashWithSpaces::class, $obj);
    }

    /** @test */
    public function it_validates_rule_is_valid()
    {
        $this->assertTrue($this->buildValidator('My-Folder')->passes());
        $this->assertTrue($this->buildValidator('My Folder')->passes());
        $this->assertTrue($this->buildValidator('My Folder-isCool')->passes());
    }

    /** @test */
    public function it_validates_invalid_rule()
    {
        $this->assertFalse($this->buildValidator('My-Folder @email')->passes());
        $this->assertFalse($this->buildValidator('My-Folder @email|}{')->passes());
    }

    /** @test */
    public function it_has_correct_error_message()
    {
        $message = $this->buildValidator('My-Folder @email|}{')->getMessageBag();

        $this->assertEquals(
            'The name may only contain letters, numbers, dashes and spaces.',
            $message->get('name')[0]
        );
    }

    public function buildValidator($folderName)
    {
        $app = new Container();
        $app->singleton('app', 'Illuminate\Container\Container');
        $translator = new Translator(new FileLoader(new Filesystem(), null), 'en');
        $validator = (new Validator($translator))->make(['name' => $folderName], [
            'name' => ['required', new AlphaDashWithSpaces()],
        ]);

        return $validator;
    }
}
