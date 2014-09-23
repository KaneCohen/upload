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
     * Returns file metadata.
     *
     * @return array
     */
    public function getMetadata();

    /**
     * Returns file MIME type.
     *
     * @return string
     */
    public function getMimetype();

    /**
     * Returns file extensions.
     *
     * @return string
     */
    public function getExtension();

    /**
     * Checks if file is available in the store by param name.
     *
     * @return bool
     */
    public function isAvailable();

    /**
     * Checks if file exists.
     *
     * @return bool
     */
	public function exists();

    /**
     * Saves file to the location.
     *
     * @param  string $path
     * @return bool
     */
    public function save($path);

    /**
     * Moves file to the new location.
     *
     * @param  string $path
     * @return mixed
     */
    public function move($path);

    /**
     * Deletes file.
     *
     * @return bool
     */
    public function delete();
}