<?php

use Mockery as m;

use Cohensive\Upload\File;

class FileTest extends PHPUnit_Framework_TestCase
{
    public function testSize()
    {
        $filepath = __DIR__ . '/file.txt';
        $file = new File($filepath, 'file');

        $this->assertTrue($file->isExists());
        $this->assertEquals($file->getFileinfo('size'), filesize($filepath));
    }
}
