<?php

namespace App\Helpers\Import\Parsers;

use App\Listeners\Import\JsonImportListener;
use JsonStreamingParser\Parser;
use Illuminate\Support\Facades\Storage;

/**
 * Class Json
 *
 * @package App\Helpers\Import\Parsers
 *
 * @author  Kacper Kowalski kacperk83@gmail.com
 */
class Json extends ImporterAbstract
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
     * @return array
     * @throws \Exception
     */
    public function parse(): array
    {
        //return json_decode($contents);
        $stream = Storage::readStream($this->getFilePath());

        try {
            $listener = new JsonImportListener(function ($jsonDocument) {
                yield $jsonDocument;
            });
            $parser = new Parser($stream, $listener);
            $parser->parse();
        } catch (\Exception $e) {
            fclose($stream);
            throw $e;
        }
    }
}
