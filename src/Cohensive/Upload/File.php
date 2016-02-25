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
     * Original file name.
     *
     * @var string
     */
    protected $origname;

    /**
     * Constructor.
     *
     * @param string $filepath
     */
    public function __construct($filepath, $origname)
    {
        $this->filepath = $filepath;
        $this->origname = $origname;
    }

    /**
     * Moves file to the new location.
     *
     * @param $path
     * @return bool
     */
    public function move($filepath)
    {
        if ($this->isExists()) {
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
        if ($this->isExists()) {
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
        if ($this->isExists()) {
            $info = new SplFileInfo($this->filepath);

            $normalInfo = $this->normalizeFileInfo($info);

            if ($key) {
                if (isset($normalInfo[$key])) return $normalInfo[$key];
                return $default;
            }
            return $normalInfo;
        }
        return null;
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
     * Returns origname.
     *
     * @return string
     */
    public function getOrigname()
    {
        return $this->origname;
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
    public function isExists()
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
            'path' => $file->getPathInfo()->getRealPath() . DIRECTORY_SEPARATOR,
            'filename' => $file->getFilename(),
            'filepath' => $file->getRealPath(),
            'name' => substr($file->getFilename(), 0, strrpos($file->getFilename(), '.')),
            'origname' => substr($this->origname, 0, strrpos($this->origname, '.')),
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
