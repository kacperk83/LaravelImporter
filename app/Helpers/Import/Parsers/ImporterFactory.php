<?php

namespace App\Helpers\Import\Parsers;

use Exception;
use Illuminate\Support\Str;

/**
 * Class ImporterFactory
 *
 * @package App\Helpers\Import\Parsers
 *
 * @author  Kacper Kowalski kacperk83@gmail.com
 */
class ImporterFactory
{

    /**
     * @param string $identifier
     *
     * @return mixed
     * @throws Exception
     */
    public static function build(string $identifier)
    {
        $classToBuild = __NAMESPACE__ . '\\' . Str::studly($identifier);

        if (class_exists($classToBuild)) {
            return new $classToBuild();
        }

        throw new Exception('Class not found for this import extension');
    }
}
