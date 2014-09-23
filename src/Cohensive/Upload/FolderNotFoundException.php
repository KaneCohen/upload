<?php

namespace Cohensive\Upload;

class FolderNotFoundException extends \Exception
{
    /*
     * @var  string  $folder
     */
    protected $folder;

    /**
     * Constructor.
     *
     * @param string     $folder
     * @param int        $code
     * @param \Exception $previous
     */
    public function __construct($folder, $code = 0, \Exception $previous = null)
    {
        $this->folder = $folder;

        parent::__construct('Folder not found: ' . $folder, $code, $previous);
    }

    /**
     * Get folder that was not found
     *
     * @return string
     */
    public function getFolder()
    {
        return $this->folder;
    }
}

