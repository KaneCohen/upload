<?php

use Mockery as m;
use Cohensive\Upload\Upload;
use Cohensive\Upload\FileHandlerFactory;

class UploadTest extends PHPUnit_Framework_TestCase
{

    public function testUploadConstructor()
    {
        $_SERVER['CONTENT_TYPE'] = true;
        $validator = m::mock('Cohensive\Upload\Validator');
        $validator->shouldReceive('setFile');
        $validator->shouldReceive('setRules');
        $validator->shouldReceive('validateOptions');
        $sanitizer = m::mock('Cohensive\Upload\Sanitizer\SanitizerInterface');
        $factory = m::mock('Cohensive\Upload\FileHandlerFactory');
        $fileHandler = m::mock('Cohensive\Upload\FileHandlerInterface');
        $fileHandler->shouldReceive('exists')->andReturn(true);
        $fileHandler->shouldReceive('getExtension')->andReturn('jpg');
        $fileHandler->shouldReceive('isAvailable')->andReturn(true);
        $fileHandler->shouldReceive('getName')->andReturn('foo');

        $file = m::mock('Cohensive\Upload\File');
        $file->shouldReceive('getFileinfo')->andReturn([
            'type' => 'file',
            'path' => '/',
            'filename' => 'foo.jpg',
            'name' => 'foo',
            'extension' => 'jpg',
            'mime' => 'image/jpeg',
            'size' => 1024,
            'timestamp' => 12345533,
            'width' => 100,
            'height' => 200
        ]);
        $fileHandler->shouldReceive('save')->andReturn($file);
        $factory->shouldReceive('make')->andReturn($fileHandler);
        $upload = new Upload($validator, $sanitizer, $factory);

        $this->assertInstanceOf('Cohensive\Upload\Upload', $upload);
    }

    public function testUploadConstructorWithoutFile()
    {
        unset($_SERVER['CONTENT_TYPE']);
        $validator = m::mock('Cohensive\Upload\Validator');
        $validator->shouldReceive('setFile');
        $validator->shouldReceive('setRules');
        $validator->shouldReceive('validateOptions');
        $sanitizer = m::mock('Cohensive\Upload\Sanitizer\SanitizerInterface');
        $factory = new FileHandlerFactory();

        $this->setExpectedException('Cohensive\Upload\FileNotFoundException');
        $upload = new Upload($validator, $sanitizer, $factory);
    }

    public function testUploadValidation()
    {
        $_SERVER['CONTENT_TYPE'] = true;
        $validator = m::mock('Cohensive\Upload\Validator');
        $validator->shouldReceive('setFile');
        $validator->shouldReceive('setRules');
        $validator->shouldReceive('validateOptions');
        $validator->shouldReceive('passes')->andReturn(true);
        $validator->shouldReceive('fails')->andReturn(false);
        $sanitizer = m::mock('Cohensive\Upload\Sanitizer\SanitizerInterface');
        $factory = m::mock('Cohensive\Upload\FileHandlerFactory');
        $fileHandler = m::mock('Cohensive\Upload\FileHandlerInterface');
        $fileHandler->shouldReceive('exists')->andReturn(true);
        $fileHandler->shouldReceive('isAvailable')->andReturn(true);
        $fileHandler->shouldReceive('getName')->andReturn('foo');
        $fileHandler->shouldReceive('getExtension')->andReturn('jpg');

        $file = m::mock('Cohensive\Upload\File');
        $file->shouldReceive('getFileinfo')->andReturn([
            'type' => 'file',
            'path' => '/',
            'filename' => 'foo.jpg',
            'name' => 'foo',
            'extension' => 'jpg',
            'mime' => 'image/jpeg',
            'size' => 1024,
            'timestamp' => 12345533,
            'width' => 100,
            'height' => 200
        ]);
        $factory->shouldReceive('make')->andReturn($fileHandler);
        $fileHandler->shouldReceive('save')->andReturn($file);
        $upload = new Upload($validator, $sanitizer, $factory);

        $this->assertInstanceOf('Cohensive\Upload\Upload', $upload);
        $this->assertTrue($upload->passes());
        $this->assertFalse($upload->fails());
    }

    public function testUploadReceive()
    {
        $_SERVER['CONTENT_TYPE'] = true;
        $validator = m::mock('Cohensive\Upload\Validator');
        $validator->shouldReceive('setFile');
        $validator->shouldReceive('setRules');
        $validator->shouldReceive('validateOptions');
        $validator->shouldReceive('passes')->andReturn(true);
        $validator->shouldReceive('fails')->andReturn(false);
        $sanitizer = m::mock('Cohensive\Upload\Sanitizer\SanitizerInterface');
        $sanitizer->shouldReceive('sanitize')->andReturn('foo');
        $factory = m::mock('Cohensive\Upload\FileHandlerFactory');
        $fileHandler = m::mock('Cohensive\Upload\FileHandlerInterface');
        $fileHandler->shouldReceive('exists')->andReturn(true);
        $fileHandler->shouldReceive('isAvailable')->andReturn(true);
        $fileHandler->shouldReceive('getName')->andReturn('foo');
        $fileHandler->shouldReceive('getExtension')->andReturn('jpg');

        $file = m::mock('Cohensive\Upload\File');
        $file->shouldReceive('getFileinfo')->andReturn([
            'type' => 'file',
            'path' => '/',
            'filename' => 'foo',
            'filepath' => 'foo',
            'origname' => 'foo',
            'name'     => 'foo',
            'extension' => 'jpg',
            'mime' => 'image/jpeg',
            'size' => 1024,
            'timestamp' => 12345533,
            'width' => 100,
            'height' => 200
        ]);
        $factory->shouldReceive('make')->andReturn($fileHandler, $fileHandler);
        $fileHandler->shouldReceive('save')->andReturn($file);
        $file->shouldReceive('move')->andReturn(true);

        $upload = new Upload($validator, $sanitizer, $factory);
        $upload->receive();

        $this->assertInstanceOf('Cohensive\Upload\Upload', $upload);
        $this->assertTrue($upload->passes());
        $this->assertFalse($upload->fails());
    }

}
