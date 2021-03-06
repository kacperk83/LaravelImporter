<?php

namespace App\Helpers\Import;

use App\Helpers\Import\Parsers\ImporterFactory;
use App\Helpers\Import\Parsers\ImporterInterface;
use Exception;

/**
 * Class ImportHelper
 *
 * @package App\Helpers\Import
 *
 * @author  Kacper Kowalski kacperk83@gmail.com
 */
class ImportHelper
{
    /**
     * Location to (temporary) store the uploaded import files
     */
    const IMPORT_LOCATION = 'imports';

    /**
     * @var array $supportedTypes
     */
    private $supportedTypes;

    /**
     * ImportHelper constructor.
     */
    public function __construct()
    {
        $this->supportedTypes = config('uploads.supported_import_types');
    }

    /**
     * @param string $extension
     *
     * @return ImporterInterface
     * @throws Exception
     */
    public function getImportParser(string $extension): ImporterInterface
    {
        //Find a supported imported for the given file extension
        foreach ($this->supportedTypes as $type) {

            /** @var ImporterInterface $importer */
            $importer = ImporterFactory::build($type);

            if ($importer->getType() == $extension) {
                return $importer;
            }
        }

        throw new Exception('File extension not supported');
    }
}
