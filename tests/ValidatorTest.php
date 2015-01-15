<?php

use Mockery as m;
use Cohensive\Upload\Validator;

class ValidatorTest extends PHPUnit_Framework_TestCase
{

    public function testVlaidatorConstructor()
    {
        $file = m::mock('Cohensive\Upload\File');
        $file->shouldReceive('exists')->andReturn(true);
        $file->shouldReceive('getFileinfo')->once();
        $validator = new Validator();
        $validator->setFile($file);

        $this->assertInstanceOf('Cohensive\Upload\Validator', $validator);
    }

    public function testVlaidatorValidationSuccess()
    {
        $file = m::mock('Cohensive\Upload\File');
        $file->shouldReceive('exists')->andReturn(true);
        $file->shouldReceive('getFileinfo')->andReturn([
            'type' => 'file',
            'path' => '/',
            'filename' => 'foo.jpg',
            'origname' => 'foo',
            'extension' => 'jpg',
            'mime' => 'image/jpeg',
            'size' => 1024,
            'timestamp' => 12345533,
            'width' => 100,
            'height' => 200
        ]);
        $validator = new Validator();
        $validator->setFile($file);

        $this->assertTrue($validator->passes());
        $this->assertFalse($validator->fails());
    }

    public function testVlaidatorValidationFail()
    {
        $file = m::mock('Cohensive\Upload\File');
        $file->shouldReceive('exists')->andReturn(true);
        $file->shouldReceive('getFileinfo')->andReturn([
            'type' => 'file',
            'path' => '/',
            'filename' => 'foo.jpg',
            'origname' => 'foo',
            'extension' => 'jpg',
            'mime' => 'image/jpeg',
            'size' => 10243532523526,
            'timestamp' => 12345533,
            'width' => 100,
            'height' => 200
        ]);
        $validator = new Validator();
        $validator->setFile($file);
        $validator->setRules(['whiteExt' => ['pdf']]);

        $this->assertFalse($validator->passes());
        $this->assertTrue($validator->fails());
        $this->assertEquals(['maxSize', 'whiteExt'], $validator->getErrors());
    }
}
