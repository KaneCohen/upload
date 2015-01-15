<?php
namespace Cohensive\Upload;

class FileSaveException extends \Exception
{
    /**
     * @var string
     */
    protected $name;

    /**
     * Constructor.
     *
     * @param string     $name
     * @param int        $code
     * @param \Exception $previous
     */
    public function __construct($name, $code = 0, \Exception $previous = null)
    {
        $this->name = $name;

        parent::__construct('Could not save file: ' . $name . '.', $code, $previous);
    }

    /**
     * Returns input name that was not found
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
}
