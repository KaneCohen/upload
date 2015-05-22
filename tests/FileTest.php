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

    public function testInfo()
    {
        $filepath = __DIR__ . '/file.txt';
        $file = new File($filepath, 'file');
        $splInfo = new SplFileInfo($filepath);
        $info = [
            'type' => 'file',
            'path' => __DIR__ . '/',
            'filename' => 'file.txt',
            'filepath' => $filepath,
            'name' => 'file',
            'origname' => '',
            'extension' => 'txt',
            'mime' => 'text/plain',
            'size' => 13,
            'timestamp' => $splInfo->getMTime(),
            'width' => null,
            'height' => null
        ];

        $this->assertEquals($info, $file->getFileinfo());
    }
}
