<?php
namespace Cohensive\Upload;

class FileHandlerFactory
{
    /**
     * Creates FileHandler based on the type of the input.
     *
     * @param  string                    $param
     * @return PostHandler|StreamHandler
     * @throws FileNotFoundException
     */
    public function make($param)
    {
        if ( ! isset($_SERVER['CONTENT_TYPE'])) {
            throw new FileNotFoundException($param);
        } else if (strpos(strtolower($_SERVER['CONTENT_TYPE']), 'multipart/') === 0) {
            return new PostFileHandler($param);
        } else {
            return new StreamFileHandler($param);
        }
    }
}
