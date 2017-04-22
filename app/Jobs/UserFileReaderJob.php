<?php

namespace App\Jobs;

use App\Models\User;
use App\Models\Creditcard;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Storage;
use App\Helpers\Import\ImportHelper;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Bus\DispatchesJobs;

/**
 * Class UserFileReaderJob
 *
 * @package App\Jobs
 *
 * @author  Kacper Kowalski kacperk83@gmail.com
 */
class UserFileReaderJob extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels, DispatchesJobs;

    /**
     * @var string $contents
     */
    private $path;

    /**
     * @param string $path
     *
     * @return $this
     */
    public function init(string $path)
    {
        $this->path = $path;

        return $this;
    }

    public function handle(ImportHelper $importHelper)
    {
        //Get importer
        $extension = explode('.', $this->path)[1];
        $importer = $importHelper->getImportParser($extension);

        //Get Filename
        $filename = explode('/', $this->path)[1];

        //Get contents
        $contents = Storage::get($this->path);
        $parsed = $importer->parse($contents);

        //echo count($parsed);
        //var_dump($parsed[0]);
        //die();

        $processed_user_lines = array_flip(User::where('imported_from', $filename)->get()->map(function ($item, $key) {
            return $item->linenumber;
        })->all());

        $processed_creditcard_lines = array_flip(Creditcard::where('imported_from', $filename)->get()->map(function (
            $item,
            $key
        ) {
            return $item->linenumber;
        })->all());

        //TODO: Loop over the users and store them conditionally (including line number & filename)
        //Conditions:
        // 1. Line&filename not found in DB
        // 2. age betweek 18 - 65
        foreach ($parsed as $line => $user) {





        }
    }
}
