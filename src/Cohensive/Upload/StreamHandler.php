<?php
namespace Cohensive\Upload;

class StreamHandler extends FileHandler
{
    /**
     * Constructor.
     *
     * @param string $paramName
     */
    public function __construct($paramName = 'files')
    {
        parent::__construct($paramName);
        $this->store = $_GET;
    }

    /**
     * Returns file name.
     *
     * @return string
     */
    public function getName()
    {
        if ($this->isAvailable()) {
            return $this->store[$this->paramName];
        }
    }

    /**
     * Returns file size.
     *
     * @return int
     * @throws \Exception
     */
    public function getSize()
    {
        if (isset($_SERVER['CONTENT_LENGTH'])) {
            return (int) $_SERVER['CONTENT_LENGTH'];
        } else {
            throw new \Exception('Getting content length is not supported.');
        }
    }

    /**
     * Saves file to the location.
     *
     * @param  string $path
     * @return bool
     */
    public function save($path)
    {
        $input = fopen('php://input', 'r');
        $target = fopen($path, 'w');

        fseek($target, 0, SEEK_SET);
        $realSize = stream_copy_to_stream($input, $target);
        fclose($input);

        // Expected file size and actual file size must match.
        if ($realSize !== $this->getSize()) {
            unlink($target);
            return false;
        }

        chmod($path, 0644);
        $this->path = $path;

        return true;
    }
}
