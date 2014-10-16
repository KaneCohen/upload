<?php
namespace Cohensive\Upload;

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
     * Full path to the file.
     *
     * @var string
     */
    protected $path;

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
     * Moves file to the new location.
     *
     * @param $path
     * @return bool
     */
    public function move($path)
    {
        if ($this->path) {
            $success = rename($this->path, $path);
            $this->path = $path;
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
        if ($this->path) {
            return unlink($this->path);
        }
        return false;
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
     * Returns file metadata.
     *
     * @return array
     */
    public function getMetadata()
    {
        $info = new \SplFileInfo($this->path);

        return $this->normalizeFileInfo($info);
    }

    /**
     * Returns file MIME type.
     *
     * @return string
     */
    public function getMimetype()
    {
        $finfo = new \Finfo(FILEINFO_MIME_TYPE);

        return $finfo->file($this->path);
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

    /**
     * Checks if file exists.
     *
     * @return bool
     */
    public function exists()
    {
        return $this->path !== null;
    }

    /**
     * Turns file info into a structured array containing file meradata.
     *
     * @param \SplFileInfo $file
     * @return array
     */
    protected function normalizeFileInfo(\SplFileInfo $file)
    {
        $size = getimagesize($this->path);
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
