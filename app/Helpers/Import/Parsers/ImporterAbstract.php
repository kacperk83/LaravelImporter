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
     * @var string|null $type
     */
    protected $type = null;

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
     * @return mixed
     */
    public function getCallback()
    {
        return $this->callback;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return static::$type;
    }

    public function parse()
    {
    }
}
