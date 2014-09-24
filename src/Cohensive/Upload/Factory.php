<?php
namespace Cohensive\Upload;

use Cohensive\Upload\Sanitizer\SanitizerInterface;

class Factory
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
    public function make(array $options = [], array $rules = [])
    {
        $validator = new Validator($rules);
        $sanitizer = new LaravelStrSanitizer();
        $fileFactory = new FileHandlerFactory();

        $options = array_merge($this->options, $options);
        return new Upload($validator, $sanitizer, $fileFactory, $options);
    }
}
