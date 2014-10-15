<?php
namespace Cohensive\Upload;

use Cohensive\Upload\Sanitizer\LaravelStrSanitizer;

class LaravelFactory
{
    /**
     * Array of options.
     *
     * @var array
     */
    protected $options;

    /**
     * Constructor.
     *
     * @param array $options
     */
    public function __construct(array $options = [])
    {
        $this->options = $options;
    }

    /**
     * Creates new Upload instance.
     *
     * @param array $options
     * @param array $rules
     * @return Upload
     */
    public function make(array $rules = [], array $options = [])
    {
        $validator = new Validator($rules);
        $sanitizer = new LaravelStrSanitizer();
        $fileFactory = new FileHandlerFactory();

        $options = array_merge($this->options, $options);
        return new Upload($validator, $sanitizer, $fileFactory, $options);
    }
}
