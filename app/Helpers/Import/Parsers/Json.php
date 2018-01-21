<?php

namespace App\Helpers\Import\Parsers;

use App\Exceptions\InternalServerException;
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
    protected static $type = 'json';

    /**
     * @throws \Exception
     */
    public function parse(): void
    {
        $stream = Storage::readStream($this->getFilePath());

        try {
            $listener = new JsonImportListener($this->getCallback());
            $parser = new Parser($stream, $listener);
            $parser->parse();

        } catch (\Exception $e) {
            fclose($stream);
            throw new InternalServerException(trans('errors.import.json', ['filePath' => $this->getFilePath()]), $e);
        }
    }
}
