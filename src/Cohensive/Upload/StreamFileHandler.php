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
     * @param  string $filepath
     * @return bool
     */
    public function save($filepath)
    {
        $input = fopen('php://input', 'r');
        $target = fopen($filepath, 'w');

        fseek($target, 0, SEEK_SET);
        $realSize = stream_copy_to_stream($input, $target);
        fclose($input);

        // Expected file size and actual file size must match.
        if ($realSize !== $this->getSize()) {
            unlink($target);
            throw new FileSaveException($this->getName());
        }

        chmod($filepath, 0644);
        return new File($filepath);
    }
}
