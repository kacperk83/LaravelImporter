<?php

namespace App\Helpers\Import\Parsers;

/**
 * Class Json
 *
 * @package App\Helpers\Import\Parsers
 *
 * @author  Kacper Kowalski kacperk83@gmail.com
 */
class Json implements ImporterInterface
{
    /**
     * @var string $type
     */
    private $type = 'json';

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * Parse the contents to array
     *
     * @param string $contents
     *
     * @return array
     */
    public function parse(string $contents): array
    {
        return json_decode($contents);
    }
}
