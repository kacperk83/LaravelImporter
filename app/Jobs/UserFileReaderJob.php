<?php

namespace App\Jobs;

use App\Helpers\Import\Parsers\ImporterInterface;
use Exception;
use App\Models\User;
use Carbon\Carbon;
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
        //Get importer
        $extension = explode('.', $this->path)[1];
        
        /** @var ImporterInterface $importer */
        $importer = $importHelper->getImportParser($extension);

        $importer->setFilePath($this->path);

        //@todo: make this parse function iterable (yield?)
        $parsed = $importer->parse();

        foreach($parsed as $item){
            var_dump($item);
        }

        die();
        
        //@todo: 1. Move import related info (filename+linenumber) to UserImportLocation & CreditcardImportLocation
        //@todo: instead of dropping it in the User model
        //@todo: 2. fix bug: in case we don't have user we still try to create creditcard & associate it.
        //@todo: 3. Maybe use a checksum hash instead of a filename.

        //Get the already processed import lines for this filename and index them (user)
        $processedUserLines = array_flip(User::where('imported_from', $filename)->get()->map(function ($item, $key) {
            return $item->linenumber;
        })->all());

        //Idem. But this time for the creditcards.
        $processedCardLines = array_flip(Creditcard::where('imported_from', $filename)->get()->map(function (
            $item,
            $key
        ) {
            return $item->linenumber;
        })->all());

        /**
         * Loop over user import and insert into DB
         *
         * Conditions:
         * 1. Line&filename not found in DB
         * 2. age betweek 18 - 65 or unknown
         */
        foreach ($parsed as $line => $user) {
            try {
                $dateOfBirth = Carbon::parse($user->date_of_birth);
                $age = $dateOfBirth->diffInYears(Carbon::now());
            } catch (Exception $e) {
                $dateOfBirth = null;
                $age = null;
            }

            //Add the user
            if (!isset($processedUserLines[$line]) &&
                (is_null($user->date_of_birth) || is_null($age) || ($age > 18 && $age < 65))) {
                $userModel = User::create([
                    'name' => $user->name,
                    'address' => $user->address,
                    'checked' => $user->checked,
                    'description' => $user->description,
                    'interest' => $user->interest,
                    'date_of_birth' => $dateOfBirth,
                    'email' => $user->email,
                    'account' => $user->account,
                    'linenumber' => $line,
                    'imported_from' => $filename
                ]);

                $userModel->save();
            }

            //Add creditcard and associate with user
            //Optionally add extra regex to filter on three successive identical numbers
            if (!isset($processedCardLines[$line]) && isset($user->credit_card)) {

                /** @var Creditcard $card */
                $card = Creditcard::create([
                    'type' => $user->credit_card->type,
                    'number' => $user->credit_card->number,
                    'name' => $user->credit_card->name,
                    'expiration_date' => Carbon::parse($user->credit_card->expirationDate),
                    'linenumber' => $line,
                    'imported_from' => $filename
                ]);

                $card->user()->associate($userModel);
                $card->save();
            }
        }

        //Verwijder tenslotte het geuploade bestand
        Storage::delete($this->path);
    }
}
