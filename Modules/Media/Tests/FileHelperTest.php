<?php  namespace Modules\Media\Tests;

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
}
