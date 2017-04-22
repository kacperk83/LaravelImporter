<?php

namespace App\Jobs;

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

        $processedUserLines = array_flip(User::where('imported_from', $filename)->get()->map(function ($item, $key) {
            return $item->linenumber;
        })->all());

        $processedCardLines = array_flip(Creditcard::where('imported_from', $filename)->get()->map(function (
            $item,
            $key
        ) {
            return $item->linenumber;
        })->all());

        //Loop over user import and insert into DB
        //
        //Conditions:
        // 1. Line&filename not found in DB
        // 2. age betweek 18 - 65 or unknown
        foreach ($parsed as $line => $user) {
            $dateOfBirth = Carbon::parse($user->date_of_birth);

            $age = $dateOfBirth->diffInYears(Carbon::now());

            //Als er geen user is, ga door naar volgende regel
            if (is_null($user)) {
                continue;
            }

            //voeg user toe
            if (!isset($processedUserLines[$line]) && (is_null($user->date_of_birth) || ($age > 18 && $age < 65))) {
                $user = User::create([
                    'name' => $user->name,
                    'address' => $user->address,
                    'checked' => $user->checked,
                    'description' => $user->description,
                    'interest' => 'bla',
                    // 'interest' => $user->interest,
                    'date_of_birth' => $dateOfBirth,
                    'email' => $user->email,
                    'account' => $user->account,
                    'linenumber' => $line,
                    'imported_from' => $filename
                ]);

                $user->save();
            }

            //voeg creditcard toe en koppel aan user
            //eventueel extra regex toevoegen in conditie om te filteren op drie opeenvolgende zelfde cijfers
            if (!isset($processedCardLines[$line]) && isset($user->credit_card)) {

                /** @var Creditcard $card */
                $card = Creditcard::create([
                    'type' => $user->credit_card->type,
                    'number' => $user->credit_card->number,
                    'name' => $user->credit_card->name,
                    'expiration_date' => $user->credit_card->expirationDate,
                    'linenumber' => $line,
                    'imported_from' => $filename
                ]);
                
                $card->user()->associate($user);
                $card->save();
            }
        }
    }
}
