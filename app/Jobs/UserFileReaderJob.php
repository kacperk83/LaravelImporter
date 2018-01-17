<?php

namespace App\Jobs;

use App\Helpers\Import\Parsers\ImporterInterface;
use App\Jobs\Closures\UserImportClosure;
use App\Models\UserImportLocation;
use App\Models\CreditcardImportLocation;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use App\Helpers\Import\ImportHelper;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Exception;

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
     * Instantiate job
     *
     * @param string $path
     *
     * @return $this
     */
    public function init(string $path)
    {
        $this->path = $path;

        return $this;
    }

    /**
     * Execute job
     *
     * @param ImportHelper $importHelper
     *
     * @throws Exception
     */
    public function handle(ImportHelper $importHelper)
    {
        //Calculate the md5 that corresponds to the import file (first we need to translate the short path into a full
        //path
        $fileHash = File::hash(Storage::path($this->path));

        //Get the already processed document numbers for this file path and index them (user)
        $processedUserDocNumbers = array_flip(array_merge(
            UserImportLocation::where('file_hash', $fileHash)->get()->map(function ($item, $key) {
                return $item->document_id;
            })->all()
        ));

        //Idem. But this time for the creditcards.
        $processedCardDocNumbers = array_flip(array_merge(
            CreditcardImportlocation::where('file_hash', $fileHash)->get()->map(function ($item, $key) {
                return $item->document_id;
            })->all()
        ));


        //Get the right importer for this file type
        $extension = explode('.', $this->path)[1];

        /** @var ImporterInterface $importer */
        $importer = $importHelper->getImportParser($extension);

        //Set the path of the file so the importer can retrieve it
        $importer->setFilePath($this->path);

        //The following function class closure will be called for every new document that is retrieved from the
        //import file. Keep track of the document number so we can associate imported user data
        $docNumber = 0;

        //Set the closure which contains logic for saving every user that we find in the import file (and instantiate
        //it with some initial data)
        $userImport = new UserImportClosure(
            $processedUserDocNumbers,
            $processedCardDocNumbers,
            $this->path,
            $docNumber,
            $fileHash
        );
        $importer->setCallback($userImport);

        //Start parsing
        $importer->parse();

        //Finally remove uploaded file
        Storage::delete($this->path);
    }
}
