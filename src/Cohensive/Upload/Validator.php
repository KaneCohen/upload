<?php
namespace Cohensive\Upload;

use Cohensive\Upload\FileHandlerInterface;
use Cohensive\Upload\FileNotFoundException;
use Cohensive\Upload\FolderNotFoundException;
use Cohensive\Upload\FolderNotWritableException;

class Validator
{
    /**
     * File handler.
     *
     * @var PostFileHandler|StreamFileHandler
     */
    protected $file;

    /**
     * File metadata.
     *
     * @var array
     */
    protected $metadata;

    /**
     * List of errors after validation.
     *
     * @var array
     */
    protected $errors = [];

    /**
     * Default Upload Validator rules.
     *
     * @var array
     */
    protected $rules = [
        'minSize'   => 0,        // Minimum filesize.
        'maxSize'   => 10485760, // Maximum filesize: 10MB.
        'maxWidth'  => 0,        // Maximum image width if file is an image.
        'maxHeight' => 0,        // Maximum image height if file is an image.
        'minWidth'  => 0,        // Minimum image width if file is an image.
        'minHeight' => 0,        // Minimum image height if file is an image.
        'width'     => [],       // Image must have exact width (use array to set multiple).
        'height'    => [],       // Image must have exact height (use array to set multiple).
        'whiteExt'  => ['jpg', 'jpeg', 'gif', 'png'], // Array of allowed extensions.
        'blackExt'  => []                             // Array of disallowed extensions.
    ];

    /**
     * Constructor.
     *
     * @param array $rules
     */
    public function __construct(array $rules = array())
    {
        $this->rules = array_merge($this->rules, $rules);
    }

    /**
     * Validates File against rules.
     *
     * @return bool
     * @throws FileNotFoundException
     */
    public function passes()
    {
        if ($this->file === null || ! $this->file->exists()) {
            throw new FileNotFoundException($this->options['paramName']);
        }

        $this->emptyErrors();
        $this->metadata = $this->file->getMetadata();

        foreach ($this->rules as $rule => $param) {
            $this->validate($rule, $param);
        }

        $this->validateServerSize();

        return empty($this->errors);
    }

    /**
     * Validates file against the rules.
     *
     * @return bool
     */
    public function fails()
    {
        return ! $this->passes();
    }

    /**
     * Validates Upload options.
     *
     * @param  array $options
     * @throws FolderNotWritableException
     * @throws FolderNotFoundException
     */
    public function validateOptions(array $options)
    {
        if (! isset($options['uploadDir'])) {
            throw new FolderNotFoundException($options['uploadDir']);
        }

        if (isset($options['uploadDir']) && ! is_writable($options['uploadDir'])) {
            throw new FolderNotWritableException($options['uploadDir']);
        }

        if (! isset($options['tmpDir'])) {
            throw new FolderNotFoundException($options['tmpDir']);
        }

        if (isset($options['tmpDir']) && ! is_writable($options['tmpDir'])) {
            throw new FolderNotWritableException($options['tmpDir']);
        }
    }

    /**
     * Sets file.
     *
     * @param FileHandlerInterface $file
     */
    public function setFile(FileHandlerInterface $file)
    {
        $this->file = $file;
    }

    /**
     * Sets Validator rules.
     *
     * @param array $rules
     */
    public function setRules(array $rules)
    {
        $this->rules = array_merge($this->rules, $rules);
    }

    /**
     * Returns Validator rules.
     *
     * @return array
     */
    public function getRules()
    {
        return $this->rules;
    }

    /**
     * Adds Validator error.
     *
     * @param  string $error
     * @return void
     */
    public function addError($error)
    {
        $this->errors[] = $error;
    }

    /**
     * Returns list of errors after validation.
     *
     * @return array
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * Empties array of errors.
     *
     * @return void
     */
    protected function emptyErrors()
    {
        $this->errors = [];
    }

    /**
     * Validate rule.
     *
     * @param string $rule
     * @param mixed  $param
     */
    protected function validate($rule, $param)
    {
        $method = 'validate' . ucfirst($rule);
        if ($param > 0 && ! $this->$method($param)) {
            $this->addError($rule);
        }
    }

    /**
     * Validates file size against min size rule.
     *
     * @param  int $minSize
     * @return bool
     */
    protected function validateMinSize($minSize)
    {
        return $this->metadata['size'] >= (int) $minSize;
    }

    /**
     * Validates file size against max size rule.
     *
     * @param  int $maxSize
     * @return bool
     */
    protected function validateMaxSize($maxSize)
    {
        return $this->metadata['size'] <= (int) $maxSize;
    }

    /**
     * Validates server settings.
     *
     * @return void
     */
    protected function validateServerSize()
    {
        $postSize = $this->toBytes(ini_get('post_max_size'));
        $uploadSize = $this->toBytes(ini_get('upload_max_filesize'));

        if ($postSize < $this->rules['maxSize'] || $uploadSize < $this->rules['maxSize']) {
            $this->addError('serverSizeLimit');
        }
    }

    /**
     * Validate file width against width rule.
     *
     * @param  int|array $width
     * @return bool
     */
    protected function validateWidth($width)
    {
        if (is_array($width) && ! empty($width)) {
            return in_array($this->metadata['width'], $width);
        } else {
            if (is_array($width)) return true;
            return $this->metadata['width'] === (int) $width;
        }
    }

    /**
     * Validates file height against height rule.
     *
     * @param  int|array $height
     * @return bool
     */
    protected function validateHeight($height)
    {
        if (is_array($height) && ! empty($height)) {
            return in_array($this->metadata['height'], $height);
        } else {
            if (is_array($height)) return true;
            return $this->metadata['height'] === (int) $height;
        }
    }

    /**
     * Validates file width against min width rule.
     *
     * @param  int $minWidth
     * @return bool
     */
    protected function validateMinWidth($minWidth)
    {
        return $this->metadata['width'] >= (int) $minWidth;
    }

    /**
     * Validates file height against min height rule.
     *
     * @param $minHeight
     * @return bool
     */
    protected function validateMinHeight($minHeight)
    {
        return $this->metadata['height'] >= (int) $minHeight;
    }

    /**
     * Validates file width against max width rule.
     *
     * @param  int $maxWidth
     * @return bool
     */
    protected function validateMaxWidth($maxWidth)
    {
        return $this->metadata['width'] <= (int) $maxWidth;
    }

    /**
     * Validates file height against max height rule.
     *
     * @param  int $maxHeight
     * @return bool
     */
    protected function validateMaxHeight($maxHeight)
    {
        return $this->metadata['height'] <= (int) $maxHeight;
    }

    /**
     * Validates file extension against whitelisted extensions.
     *
     * @param  string|array $exts
     * @return bool
     */
    protected function validateWhiteExt($exts)
    {
        if (is_array($exts)) {
            return in_array($this->metadata['extension'], $exts);
        } else {
            return $this->metadata['extension'] === $exts;
        }
    }

    /**
     * Validates file extension against blacklisted extensions.
     *
     * @param string|array $exts
     * @return bool
     */
    protected function validateBlackExt($exts)
    {
        if (is_array($exts)) {
            return ! in_array($this->metadata['extension'], $exts);
        } else {
            return $this->metadata['extension'] !== $exts;
        }
    }

    /**
     * Convert a given size with units to bytes.
     *
     * @param  string $str
     * @return int
     */
    protected function toBytes($str)
    {
        $val = trim($str);
        $last = strtolower($str[strlen($str)-1]);
        switch($last) {
            case 'g': $val *= 1024;
            case 'm': $val *= 1024;
            case 'k': $val *= 1024;
        }
        return $val;
    }
}
