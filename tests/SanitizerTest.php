<?php

use Mockery as m;
use Cohensive\Upload\Sanitizer\AbstractSanitizer;

class SanitizerTest extends PHPUnit_Framework_TestCase
{
    public function testAbstractSanitizerConstructorWithSpecifiedMethod()
    {
        $transliterator = new Transliterator();
        $sanitizer = new AbstractSanitizer($transliterator, 'foo');

        $this->assertInstanceOf('Cohensive\Upload\Sanitizer\AbstractSanitizer', $sanitizer);
    }

    public function testAbstractSanitizerConstructorWithInstanceSanitizer()
    {
        $transliterator = new TransliteratorInstance();
        $sanitizer = new AbstractSanitizer($transliterator);

        $this->assertInstanceOf('Cohensive\Upload\Sanitizer\AbstractSanitizer', $sanitizer);
    }

    public function testAbstractSanitizerConstructorWithStaticSanitizer()
    {
        $transliterator = new TransliteratorStatic();
        $sanitizer = new AbstractSanitizer($transliterator);

        $this->assertInstanceOf('Cohensive\Upload\Sanitizer\AbstractSanitizer', $sanitizer);
    }

    public function testAbstractSanitizerConstructorWithoutSanitizerMethod()
    {
        $transliterator = m::mock('Sanitizer');
        $this->setExpectedException('Exception');
        $sanitizer = new AbstractSanitizer($transliterator);
    }
}

class Transliterator
{
    public function foo($string)
    {
        return $string;
    }
}

class TransliteratorInstance
{
    public function slug($string)
    {
        return $string;
    }
}

class TransliteratorStatic
{
    public static function slug($string)
    {
        return $string;
    }
}
