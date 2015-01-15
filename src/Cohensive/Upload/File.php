<?php
namespace Cohensive\Upload;

use Finfo;
use SplFileInfo;

class File
{
    /**
     * Full path to the file.
     *
     * @var string
     */
    protected $filepath;

    /**
     * Constructor.
     *
     * @param string $filepath
     */
    public function __construct($filepath)
    {
        $this->filepath = $filepath;
    }

    /**
     * Moves file to the new location.
     *
     * @param $path
     * @return bool
     */
    public function move($filepath)
    {
        if ($this->exists()) {
            $success = rename($this->getFilepath(), $filepath);
            if ($success) {
                $this->filepath = $filepath;
            }
            return $success;
        }
        return false;
    }

    /**
     * Deletes file.
     *
     * @return bool
     */
    public function delete()
    {
        if ($this->exists()) {
            $this->filepath = null;
            return unlink($this->filepath);
        }
        return false;
    }

    /**
     * Returns file info.
     *
     * @return array
     */
    public function getFileinfo($key = null, $default = null)
    {
        $info = new SplFileInfo($this->filepath);

        $normalInfo = $this->normalizeFileInfo($info);

        if ($key) {
            if (isset($normalInfo[$key])) return $normalInfo[$key];
            return $default;
        }
        return $normalInfo;
    }

    /**
     * Sets filepath.
     *
     * @return array
     */
    public function setFilepath($filepath)
    {
        return $this->filepath = $filepath;
    }

    /**
     * Returns filepath.
     *
     * @return string
     */
    public function getFilepath()
    {
        return $this->filepath;
    }

    /**
     * Returns file MIME type.
     *
     * @return string
     */
    public function getMimetype()
    {
        $finfo = new Finfo(FILEINFO_MIME_TYPE);

        return $finfo->file($this->filepath);
    }

    /**
     * Returns file extension based on the input name.
     *
     * @return string
     */
    public function getExtension()
    {
        return $this->getFileinfo('extension');
    }

    /**
     * Checks if file exists.
     *
     * @return bool
     */
    public function exists()
    {
        return $this->filepath && file_exists($this->filepath);
    }

    /**
     * Turns file info into a structured array containing file meradata.
     *
     * @param SplFileInfo $file
     * @return array
     */
    protected function normalizeFileInfo(SplFileInfo $file)
    {
        $size = getimagesize($this->filepath);
        $normalized = [
            'type' => $file->getType(),
            'path' => $file->getPathname(),
            'filename' => $file->getFilename(),
            'filepath' => $file->getPathname(),
            'name' => substr($file->getFilename(), 0, strrpos($file->getFilename(), '.')),
            'origname' => substr($this->getName(), 0, strrpos($this->getName(), '.')),
            'extension' => $file->getExtension(),
            'mime' => $this->getMimetype(),
            'size' => $file->getSize(),
            'timestamp' => $file->getMTime(),
            'width' => $size[0],
            'height' => $size[1]
        ];

        return $normalized;
    }
}
