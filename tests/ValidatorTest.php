<?php

use Mockery as m;
use Cohensive\Upload\Validator;

class ValidatorTest extends PHPUnit_Framework_TestCase
{

    public function testVlaidatorConstructor()
    {
        $fileHandler = m::mock('Cohensive\Upload\FileHandlerInterface');
        $fileHandler->shouldReceive('exists')->andReturn(true);
        $fileHandler->shouldReceive('getMetadata')->once();
        $validator = new Validator();
        $validator->setFile($fileHandler);

        $this->assertInstanceOf('Cohensive\Upload\Validator', $validator);
    }

    public function testVlaidatorValidationSuccess()
    {
        $fileHandler = m::mock('Cohensive\Upload\FileHandlerInterface');
        $fileHandler->shouldReceive('exists')->andReturn(true);
        $fileHandler->shouldReceive('getMetadata')->andReturn([
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
        $validator->setFile($fileHandler);

        $this->assertTrue($validator->passes());
        $this->assertFalse($validator->fails());
    }

    public function testVlaidatorValidationFail()
    {
        $fileHandler = m::mock('Cohensive\Upload\FileHandlerInterface');
        $fileHandler->shouldReceive('exists')->andReturn(true);
        $fileHandler->shouldReceive('getMetadata')->andReturn([
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
        $validator->setFile($fileHandler);
        $validator->setRules(['whiteExt' => ['pdf']]);

        $this->assertFalse($validator->passes());
        $this->assertTrue($validator->fails());
        $this->assertEquals(['maxSize', 'whiteExt'], $validator->getErrors());
    }
}
