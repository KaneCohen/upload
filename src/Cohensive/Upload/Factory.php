<?php
namespace Cohensive\Upload;

use Cohensive\Upload\Sanitizer\SanitizerInterface;

class Factory
{
    /**
     * File Validator.
     *
     * @var \Cohensive\Upload\Validator;
     */
    protected $validator;

    /**
     * String sanitizer.
     *
     * @var \Cohensive\Upload\Sanitizer\SanitizerInterface
     */
    protected $sanitizer;

    /**
     * File Handler Factory.
     *
     * @var \Cohensive\Upload\FileHandlerFactory
     */
    protected $fileFactory;

    /**
     * Array of options.
     *
     * @var array
     */
    protected $options;

    /**
     * Constructor.
     *
     * @param Validator          $validator
     * @param SanitizerInterface $sanitizer
     * @param FileHandlerFactory $fileFactory
     */
    public function __construct(Validator $validator,
        SanitizerInterface $sanitizer,
        FileHandlerFactory $fileFactory,
        array $options = array()
    ) {
        $this->validator = $validator;
        $this->sanitizer = $sanitizer;
        $this->fileFactory = $fileFactory;
        $this->options = $options;
    }

    /**
     * Creates new Upload instance.
     *
     * @param array $options
     * @param array $rules
     * @return Upload
     */
    public function make(array $options = array(), array $rules = array())
    {
        $this->validator->setRules($rules);
        $options = array_merge($this->options, $options);
        return new Upload($this->validator, $this->sanitizer, $this->fileFactory, $options);
    }
}
