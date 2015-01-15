<?php
namespace Cohensive\Upload;

class PostHandler extends FileHandler
{
    /**
     * Constructor.
     *
     * @param string $paramName
     */
    public function __construct($paramName = 'files')
    {
        parent::__construct($paramName);
        $this->store = $_FILES;
    }

    /**
     * Returns file name.
     *
     * @return string
     */
    public function getName()
    {
        if ($this->isAvailable()) {
            return $this->store[$this->paramName]['name'];
        }
    }

    /**
     * Returns file size.
     *
     * @return int
     */
    public function getSize()
    {
        return (int) $this->store[$this->paramName]['size'];
    }

    /**
     * Saves file to the location.
     *
     * @param  string $filepath
     * @return bool
     */
    public function save($filepath)
    {
        $success = move_uploaded_file($this->store[$this->paramName]['tmp_name'], $filepath);
        chmod($filepath, 0644);
        if ($success) {
            return new File($filepath);
        }
        throw new FileSaveException($this->getName());
    }
}
