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
    protected $filePath;

    public function setFilePath(string $path)
    {
        $this->filePath = $path;
    }

    protected function getFilePath()
    {
        return $this->filePath;
    }
    
    public function getType(): string
    {
    }
    
    public function parse()
    {
    }
}
