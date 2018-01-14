<?php

namespace App\Helpers\Import\Parsers;

/**
 * Class ImporterAbstract
 *
 * @package App\Helpers\Import\Parsers
 *
 * @author  Kacper Kowalski kacperk83@gmail.com
 */
class ImporterAbstract implements ImporterInterface
{
    /**
     * @var string $filePath
     */
    protected $filePath;

    /**
     * @var $callback
     */
    protected $callback;

    /**
     * @param $path
     */
    public function setFilePath(string $path)
    {
        $this->filePath = $path;
    }

    /**
     * @return string
     */
    protected function getFilePath()
    {
        return $this->filePath;
    }

    /**
     * @param $callback
     */
    public function setCallback($callback)
    {
        $this->callback = $callback;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
    }

    public function parse()
    {
    }
}
