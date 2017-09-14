<?php
/**
 * Platform.
 * @User nicolaswidart
 * @Date 10/09/2017
 * @Time 13:22
 */

namespace Modules\Media\Tests;

use Illuminate\Container\Container;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Http\UploadedFile;
use Illuminate\Translation\FileLoader;
use Illuminate\Translation\Translator;
use Illuminate\Validation\Factory as Validator;
use Modules\Media\Validators\MaxFolderSizeRule;

final class MaxFolderSizeRuleTest extends MediaTestCase
{
    public function test_it_creates_instance_of_folder_size_validator()
    {
        $obj = new MaxFolderSizeRule();
        $this->assertInstanceOf(MaxFolderSizeRule::class, $obj);
    }

    /** @test */
    public function it_validates_max_folder_size_is_valid()
    {
        $this->app['config']->set('asgard.media.config.max-total-size', 1000); // Mocked folder size: 510

        $validator = $this->buildValidator(UploadedFile::fake()->image('avatar.jpg'));
        $this->assertTrue($validator->passes());
    }

    /** @test */
    public function it_validates_max_folder_size_is_invalid()
    {
        $this->app['config']->set('asgard.media.config.max-total-size', 100); // Mocked folder size: 510

        $validator = $this->buildValidator(UploadedFile::fake()->image('avatar.jpg'));
        $this->assertFalse($validator->passes());
    }

    public function buildValidator($directorySize)
    {
        $app = new Container();
        $app->singleton('app', 'Illuminate\Container\Container');
        $translator = new Translator(new FileLoader(new Filesystem(), null), 'en');
        $validator = (new Validator($translator))->make(['file' => $directorySize], [
            'file' => ['required', new MaxFolderSizeRule()],
        ]);

        return $validator;
    }
}
