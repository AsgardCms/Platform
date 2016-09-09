<?php

namespace Modules\Media\Tests;

use Modules\Core\Tests\BaseTestCase;
use Modules\Media\Helpers\FileHelper;

class FileHelperTest extends BaseTestCase
{
    /** @test */
    public function it_should_return_slugged_name_with_extension()
    {
        $expected = 'file-name.png';
        $name = FileHelper::slug('File Name.png');

        $this->assertEquals($expected, $name);
    }

    /** @test */
    public function it_should_return_slugged_name_when_uppercase_extension_provided()
    {
        $expected = 'file-name.png';
        $name = FileHelper::slug('File Name.PNG');

        $this->assertEquals($expected, $name);
    }

    /** @test */
    public function it_should_get_the_first_part_of_mimetype()
    {
        $this->assertEquals('image', FileHelper::getTypeByMimetype('image/png'));
        $this->assertEquals('video', FileHelper::getTypeByMimetype('video/png'));
        $this->assertEquals('document', FileHelper::getTypeByMimetype('document/png'));
    }

    /** @test */
    public function it_gets_the_correct_icon_class_for_type()
    {
        $this->assertEquals('fa-file-video-o', FileHelper::getFaIcon('video'));
        $this->assertEquals('fa-file-audio-o', FileHelper::getFaIcon('audio'));
        $this->assertEquals('fa-file', FileHelper::getFaIcon('file'));
        $this->assertEquals('fa-file', FileHelper::getFaIcon('random'));
    }
}
