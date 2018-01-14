<?php

namespace App\Jobs;

use App\Helpers\Import\Parsers\ImporterInterface;
use App\Jobs\Closures\UserImportClosure;
use App\Models\UserImportLocation;
use App\Models\CreditcardImportLocation;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Storage;
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
        //Get the already processed document numbers for this file path and index them (user)
        $processedUserDocNumbers = array_flip(array_merge(
            UserImportLocation::where('file_path', $this->path)->get()->map(function ($item, $key) {
                return $item->document_id;
            })->all()
        ));

        //Idem. But this time for the creditcards.
        $processedCardDocNumbers = array_flip(array_merge(
            CreditcardImportlocation::where('file_path', $this->path)->get()->map(function ($item, $key) {
                return $item->document_id;
            })->all()
        ));


        //Get the right importer for this file type
        $extension = explode('.', $this->path)[1];

        /** @var ImporterInterface $importer */
        $importer = $importHelper->getImportParser($extension);

        //Set the path of the file so the importer can retrieve it
        $importer->setFilePath($this->path);

        $docNumber = 0;

        //Set the callback which contains logic for saving every user that we find in the import file
        $userImport = new UserImportClosure(
            $processedUserDocNumbers,
            $processedCardDocNumbers,
            $this->path,
            $docNumber
        );
        $importer->setCallback($userImport);

        //Start parsing
        $importer->parse();

        //Finally remove uploaded file
        Storage::delete($this->path);
    }
}
