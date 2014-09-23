<?php
namespace Cohensive\Upload\Sanitizer;

class AbstractSanitizer implements SanitizerInterface
{
    protected $sanitizer;

    protected $method;

    protected $methodType;

    public function __construct($sanitizer, $method = null)
    {
        $this->sanitizer = $sanitizer;
        if ($method) {
            $this->reflectMethod($method);
        } else {
            $this->guessMethod();
        }
        if ($this->method === null) {
            throw new \Exception('Could not guess Sanitizer method.');
        }
    }

    public function sanitize($string, $separator = '_')
    {
        $sanitizer = $this->sanitizer;
        if ($this->methodType === 'static') {
            return $sanitizer::{$this->method}($string, $separator);
        } else {
            return $sanitizer->{$this->method}($string, $separator);
        }
    }

    protected function guessMethod()
    {
        $possibleMethods = ['slug', 'urlize', 'transliterate'];
        foreach ($possibleMethods as $method) {
            if ($this->reflectMethod($method)) {
                break;
            }
        }
    }

    protected function reflectMethod($method)
    {
        try {
            $reflectionMethod = new \ReflectionMethod($this->sanitizer, $method);
            $this->method = $method;
            if ($reflectionMethod->isPublic() && $reflectionMethod->isStatic()) {
                $this->methodType = 'static';
            } else {
                $this->methodType = 'non-static';
            }
            return true;
        } catch (\ReflectionException $e) {}
        return false;
    }
}
