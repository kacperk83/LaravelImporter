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
     * Parse the contents to array
     *
     * @param string $contents
     *
     * @return array
     */
    public function parse(string $contents) : array;
}
