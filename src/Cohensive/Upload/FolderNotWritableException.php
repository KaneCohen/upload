<?php

namespace Cohensive\Upload;

class FolderNotWritableException extends \Exception
{
    /**
     * @var string
     */
    protected $folder;

    /**
     * Constructor
     *
     * @param string     $folder
     * @param int        $code
     * @param \Exception $previous
     */
    public function __construct($folder, $code = 0, \Exception $previous = null)
    {
        $this->folder = $folder;

        parent::__construct('Folder not writable at: ' . $folder, $code, $previous);
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


