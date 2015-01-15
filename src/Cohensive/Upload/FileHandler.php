<?php
namespace Cohensive\Upload;

use SplFileInfo;

abstract class FileHandler implements FileHandlerInterface
{
    /**
     * Parameter name.
     *
     * @var string
     */
    protected $paramName;

    /**
     * File info location.
     *
     * @var mixed
     */
    protected $store;

    /**
     * Constructor.
     *
     * @param string $paramName
     */
    public function __construct($paramName = 'files')
    {
        $this->paramName = $paramName;
    }

    /**
     * Returns param name for the file.
     *
     * @return string
     */
    public function getParamName()
    {
        return $this->paramName;
    }

    /**
     * Returns file extension based on the input name.
     *
     * @return string
     */
    public function getExtension()
    {
        $pathinfo = pathinfo($this->getName());
        return strtolower($pathinfo['extension']);
    }

    /**
     * Checks if file is available in the store by param name.
     *
     * @return bool
     */
    public function isAvailable()
    {
        return $this->store !== null && isset($this->store[$this->paramName]);
    }
}
