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
     * Constructor.
     *
     * @param Validator          $validator
     * @param SanitizerInterface $sanitizer
     * @param FileHandlerFactory $fileFactory
     */
    public function __construct(Validator $validator, SanitizerInterface $sanitizer, FileHandlerFactory $fileFactory)
    {
        $this->validator = $validator;
        $this->sanitizer = $sanitizer;
        $this->fileFactory = $fileFactory;
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
        return new Upload($this->validator, $this->sanitizer, $this->fileFactory, $options);
    }
}