<?php
namespace Cohensive\Upload;

class FileExistsException extends \Exception
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

        parent::__construct('File with name ' . $name . ' alreadyExists.', $code, $previous);
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
