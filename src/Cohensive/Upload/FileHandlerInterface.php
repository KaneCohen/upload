<?php
namespace Cohensive\Upload;

interface FileHandlerInterface
{
    /**
     * Returns file name.
     *
     * @return string
     */
    public function getName();

    /**
     * Returns file extensions.
     *
     * @return string
     */
    public function getExtension();

    /**
     * Returns file size.
     *
     * @return int
     */
    public function getSize();

    /**
     * Checks if file is available in the store by param name.
     *
     * @return bool
     */
    public function isAvailable();

    /**
     * Saves file to the location.
     *
     * @param  string $path
     * @return bool
     */
    public function save($path);
}
