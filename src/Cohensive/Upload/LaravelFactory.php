<?php
namespace Cohensive\Upload;

use Cohensive\Upload\Sanitizer\LaravelStrSanitizer;

class LaravelFactory implements UploadFactoryInterface
{
    /**
     * Array of options.
     *
     * @var array
     */
    protected $options;

    /**
     * Array of validation rules.
     *
     * @var array
     */
    protected $rules;

    /**
     * Constructor.
     *
     * @param mixed $options
     */
    public function __construct($config = [])
    {
        $this->options = (array) $config['options'];
        $this->rules = (array) $config['rules'];
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
        $rules = array_merge($this->rules, $rules);
        $options = array_merge($this->options, $options);

        $validator = new Validator($rules);
        $sanitizer = new LaravelStrSanitizer();
        $fileFactory = new FileHandlerFactory();

        return new Upload($validator, $sanitizer, $fileFactory, $options);
    }
}
