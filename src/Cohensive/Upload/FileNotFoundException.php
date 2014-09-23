<?php

namespace Cohensive\Upload;

class FileNotFoundException extends \Exception
{
    /**
     * @var string
     */
    protected $paramName;

    /**
     * Constructor.
     *
     * @param string     $paramName
     * @param int        $code
     * @param \Exception $previous
     */
    public function __construct($paramName, $code = 0, \Exception $previous = null)
    {
        $this->paramName = $paramName;

        parent::__construct('File not found at name: ' . $this->getParamName(), $code, $previous);
    }

    /**
     * Returns file name that was not found.
     *
     * @return string
     */
    public function getParamName()
    {
        return $this->paramName;
    }
}
