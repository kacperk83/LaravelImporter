<?php

namespace App\Helpers\Import\Parsers;

use App\Jobs\Closures\ImportClosureInterface;

/**
 * Interface ImporterInterface
 *
 * @package App\Helpers\Import\Parsers
 *
 * @author  Kacper Kowalski kacperk83@gmail.com
 */
interface ImporterInterface
{
    /**
     * Get the type
     *
     * @return string
     */
    public function getType() : string;

    /**
     * @param ImportClosureInterface $callback
     */
    public function setCallback(ImportClosureInterface $callback);

    /**
     * Get the closure which will be used for the import
     *
     * @return ImportClosureInterface
     */
    public function getCallback();

    /**
     * @param string $path
     */
    public function setFilePath(string $path);

    /**
     * Parse the contents to array
     */
    public function parse(): void;
}
