<?php
namespace Cohensive\Upload;

use Cohensive\Upload\Sanitizer\SanitizerInterface;

class Upload
{
    /**
     * File Validator.
     *
     * @var Validator;
     */
    protected $validator;

    /**
     * String sanitizer.
     *
     * @var SanitizerInterface
     */
    protected $sanitizer;

    /**
     * File Handler Factory.
     *
     * @var FileHandlerFactory
     */
    protected $fileFactory;

    /**
     * Array of upload options.
     *
     * @var array
     */
    protected $options = [
        'uploadDir'   => 'uploads/',     // Folder where all uploaded files will be saved to.
        'tmpDir'      => 'uploads/tmp/', // Folder to keep files temporary for operations.
        'param'       => 'file',         // Parameter to access the file on.
        'name'        => '',             // Set new filename. Blank to use original name.
        'nameLength'  => 32,             // Set maximum length of the name. Will be cut if longer.
        'prefix'      => '',             // Add prefix to the filename..
        'suffix'      => '',             // Add suffix to the filename.
        'case'        => '',             // Convert file name to the case: 'lower', 'upper' or ''.
        'overwrite'   => false,          // If file already exists, overwrite it.
        'autoRename'  => true,           // In case if file with the same name exists append counter to the new file.
        'randomize'   => false,          // Generate random filename. Boolean or integer for string length. Default length is 10.
        'sanitize'    => true            // Sanitize filename - remove whitespaces and convert utf8 to ascii.
    ];

    /**
     * Absolute path to the upload directory.
     *
     * @var string
     */
    protected $uploadDir;

    /**
     * Absolute path to the upload tmp dir.
     *
     * @var string
     */
    protected $tmpDir;

    /**
     * @var FileHandlerInterface|null
     */
    protected $file;

    /**
     * Constructor.
     *
     * @param Validator $validator
     * @param SanitizerInterface $sanitizer
     * @param FileHandlerFactory $fileFactory
     * @param array $options
     */
    public function __construct(Validator $validator,
        SanitizerInterface $sanitizer,
        FileHandlerFactory $fileFactory,
        array $options = []
    ) {
        $this->validator = $validator;
        $this->sanitizer = $sanitizer;
        $this->fileFactory = $fileFactory;
        $this->setOptions($options);

        $this->file = $this->createTmpFile();
        if ($this->file) $this->validator->setFile($this->file);
    }

    /**
     * Passthrough to the validator checking if file passed validation.
     *
     * @return bool
     */
    public function passes()
    {
        return $this->getValidator()->passes();
    }

    /**
     * Passthrough to the validator checking if file failed validation.
     *
     * @return bool
     */
    public function fails()
    {
        return $this->getValidator()->fails();
    }

    /**
     * Returns array of errors if there are any.
     *
     * @return array
     */
    public function getErrors()
    {
        return $this->getValidator()->getErrors();
    }

    /**
     * Receive file and store in the upload dir.
     *
     * @param  array $options
     * @return FileHandlerInterface
     * @throws \Exception
     */
    public function receive(array $options = [])
    {
        $this->setOptions($options);
        if ($this->passes()) {
            $filename = $this->prepareName($this->file);
            $filepath = $this->uploadDir . $filename;

            if ($this->file->move($filepath)) {
                if (file_exists($filepath)) chmod($filepath, 0644);
            } else {
                throw new \Exception('Upload failed.');
            }
            return $this->file;
        } else {
            $this->file->delete();
            return null;
        }
    }

    /**
     * Set Upload options and directories.
     *
     * @param  array $options
     * @return void
     */
    public function setOptions(array $options = [])
    {
        $this->options = array_merge($this->options, $options);
        $this->uploadDir = realpath(rtrim($this->options['uploadDir'], '/')) . DIRECTORY_SEPARATOR;
        $this->tmpDir = realpath(rtrim($this->options['tmpDir'], '/')) . DIRECTORY_SEPARATOR;
        $this->validateOptions();
    }

    /**
     * Get Upload options.
     *
     * @return array
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * Set Validator rules.
     *
     * @param array $rules
     */
    public function setRules(array $rules)
    {
        $this->validator->setRules($rules);
    }

    /**
     * Return Validator instance.
     *
     * @return Validator
     */
    public function getValidator()
    {
        return $this->validator;
    }

    /**
     * Return file instance if available or null if not.
     *
     * @return FileHandlerInterface|null
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * Save file for validation.
     *
     * @return FileHandlerInterface
     * @throws FileNotFoundException
     */
    protected function createTmpFile()
    {
        $name = $this->generateRandomString(20);
        $handler = $this->getFileHandler();
        if (! $handler->isAvailable()) {
            throw new FileNotFoundException($handler->getParamName());
        }
        return $handler->save($this->tmpDir . $name . '.' . $handler->getExtension());
    }

    /**
     * Generate file name based on Upload options.
     *
     * @param  File $file
     * @return string
     * @throws FileExistsException
     */
    protected function prepareName(File $file)
    {
        $info = $file->getFileinfo();

        $name = $info['origname'];

        if ($this->options['name']) {
            $name = $this->options['name'];
        }

        if ($this->options['sanitize']) {
            $name = $this->sanitizer->sanitize($name);
        }

        if ($this->options['randomize']) {
            $len = $this->options['randomize'] === true ? 10 : $this->options['randomize'];
            $name = $this->generateRandomString($len);
        }

        if ($this->options['nameLength'] > 0 && strlen($this->options['name']) > $this->options['nameLength']) {
            $name = substr($name, 0, $this->options['maxLength']);
        }

        switch($this->options['case']) {
            case 'upper':
                $name = strtoupper($name);
                break;
            case 'lower':
                $name = strtolower($name);
                break;
        }

        $saveAs = [
            $this->options['prefix'],
            $name,
            $this->options['suffix'],
            '', // place for auto sequencer (file_1, file_2, file_3)
            '.',
            $info['extension']
        ];

        // check if the file already exists
        if (file_exists($this->uploadDir . implode('', $saveAs))) {
            if ($this->options['autoRename']) {
                $counter = 0;
                do {
                    $saveAs[3] = '_'.++$counter;
                }
                while (file_exists($this->uploadDir . implode('', $saveAs)));
            } else {
                if ( ! (bool) $this->options['overwrite']) {
                    throw new FileExistsException(implode('', $saveAs));
                }
            }
        }

        $name = implode('', $saveAs);

        return $name;
    }

    /**
     * Returns file handler.
     *
     * @return FileHandlerInterface
     */
    protected function getFileHandler()
    {
        return $this->fileFactory->make($this->options['param']);
    }

    /**
     * Validate Upload options
     *
     * @return void
     */
    protected function validateOptions()
    {
        $this->validator->validateOptions($this->options);
    }

    /**
     * Generate random string for tmp files.
     *
     * @param  int    $length
     * @return string
     */
    protected function generateRandomString($length = 10)
    {
        $chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $rand = '';
        while ($length >= 0) {
            $rand .= $chars[rand(0, strlen($chars) - 1)];
            $length--;
        }
        return $rand;
    }
}
