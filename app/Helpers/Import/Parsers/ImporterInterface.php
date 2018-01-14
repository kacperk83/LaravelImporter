<?php

namespace App\Helpers\Import\Parsers;

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
     * @param $callback
     */
    public function setCallback($callback);

    /**
     * @param string $path
     */
    public function setFilePath(string $path);

    /**
     * Parse the contents to array
     */
    public function parse();
}
