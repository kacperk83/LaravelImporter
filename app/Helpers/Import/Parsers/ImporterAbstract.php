<?php

namespace App\Helpers\Import\Parsers;

use App\Jobs\Closures\ImportClosureInterface;

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
     * @var ImportClosureInterface $callback
     */
    protected $callback;

    /**
     * @var string|null $type
     */
    protected static $type = null;

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
     * @param ImportClosureInterface $callback
     */
    public function setCallback(ImportClosureInterface $callback)
    {
        $this->callback = $callback;
    }

    /**
     * @return ImportClosureInterface
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

    /**
     * parse
     */
    public function parse(): void
    {
    }
}
