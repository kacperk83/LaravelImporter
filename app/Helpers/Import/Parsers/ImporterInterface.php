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
     * @param string $path
     *
     * @return mixed
     */
    public function setFilePath(string $path);

    /**
     * Parse the contents to array
     *
     *
     * @return array
     */
    public function parse();
}
