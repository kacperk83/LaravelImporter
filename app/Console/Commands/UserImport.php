<?php

namespace App\Console\Commands;

use Illuminate\Foundation\Bus\DispatchesJobs;
use App\Jobs\UserFileReaderJob;
use App\Helpers\Import\ImportHelper;
use Illuminate\Console\Command;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;

/**
 * Class UserImport
 *
 * Responsible for importing users (with creditcard) from an import file
 *
 * @package App\Console\Commands
 *
 * @author  Kacper Kowalski kacperk83@gmail.com
 */
class UserImport extends Command
{
    use DispatchesJobs;

    /**
     * @var string The name and signature of the console command.
     */
    protected $signature = 'users:import';

    private $importJob;

    /**
     * @var string $description The console command description.
     */
    protected $description = 'Import users with creditcard info from a file';

    /**
     * UserImport constructor.
     *
     * @param UserFileReaderJob $importJob
     */
    public function __construct(UserFileReaderJob $importJob)
    {
        $this->importJob = $importJob;
        parent::__construct();
    }

    /**
     * handle
     *
     * This function gets automatically executed when running this command
     */
    public function handle()
    {
        //Get the file location
        $pathWithFile = $this->ask('File location');

        //Get the filename
        $pathWithFileArr = explode('/', $pathWithFile);
        $filename = array_pop($pathWithFileArr);

        //Copy the file to the new (temporary) location
        $newFilePath = Storage::putFileAs(ImportHelper::IMPORT_LOCATION, new File($pathWithFile), $filename);

        //Run the corresponding job
        $this->dispatch($this->importJob->init($newFilePath));
    }
}
