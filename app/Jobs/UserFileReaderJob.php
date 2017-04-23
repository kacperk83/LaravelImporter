<?php

namespace App\Jobs;

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
     * Instantieer de job
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
     * Voer de job uit
     *
     * @param ImportHelper $importHelper
     *
     * @throws Exception
     */
    public function handle(ImportHelper $importHelper)
    {
        //Get importer
        $extension = explode('.', $this->path)[1];
        $importer = $importHelper->getImportParser($extension);

        //Get filename
        $filename = explode('/', $this->path)[1];

        //Get contents
        $contents = Storage::get($this->path);
        $parsed = $importer->parse($contents);

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

            //voeg user toe
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

            //voeg creditcard toe en koppel aan user
            //eventueel extra regex toevoegen in conditie om te filteren op drie opeenvolgende zelfde cijfers
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
