<?php

namespace App\Jobs\Closures;

use App\Models\UserImportLocation;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Creditcard;
use App\Models\CreditcardImportLocation;

/**
 *
 * @author Kacper Kowalski kacperk83@gmail.com
 *
 */
class UserImportClosure
{
    /**
     * @var array $processedUserDocNumbers Contains all document numbers for which we processed the users
     */
    private $processedUserDocNumbers;

    /**
     * @var array $processedCardDocNumbers Contains all document numbers for which we processed the creditcards
     */
    private $processedCardDocNumbers;

    /**
     * @var string $filePath Contains the file path of the import file (including filename)
     */
    private $filePath;

    /**
     * @var int $docNumber Keeps track of the current document number we are processing
     */
    private $docNumber;

    /**
     * @var string $fileHash The hash that corresponds to the import file
     */
    private $fileHash;

    /**
     * UserImportClosure constructor.
     *
     * @param $processedUserDocNumbers
     * @param $processedCardDocNumbers
     * @param $filePath
     * @param $docNumber
     * @param $fileHash
     */
    public function __construct($processedUserDocNumbers, $processedCardDocNumbers, $filePath, &$docNumber, $fileHash)
    {
        $this->processedUserDocNumbers = $processedUserDocNumbers;
        $this->processedCardDocNumbers = $processedCardDocNumbers;
        $this->filePath = $filePath;
        $this->docNumber = $docNumber;
        $this->fileHash = $fileHash;
    }

    /**
     * @param array $user
     */
    public function __invoke(array $user)
    {

        /**
         * Loop over user import and insert into DB
         *
         * Conditions:
         * 1. docNumber&filepath not found in DB
         * 2. age betweek 18 - 65 or unknown
         */

        //Increment the document number. This is done by reference.
        $this->docNumber++;

        try {
            $dateOfBirth = Carbon::parse($user['date_of_birth']);
            $age = $dateOfBirth->diffInYears(Carbon::now());
        } catch (\Exception $e) {
            $dateOfBirth = null;
            $age = null;
        }

        //If the age is out of our scope, early out
        if (!(is_null($user['date_of_birth']) || is_null($age) || ($age > 18 && $age < 65))) {
            return;
        }

        $userModel = null;

        //Add the user
        if (!isset($this->processedUserDocNumbers[$this->docNumber])) {
            $userModel = User::create([
                'name' => $user['name'],
                'address' => $user['address'],
                'checked' => $user['checked'],
                'description' => $user['description'],
                'interest' => $user['interest'],
                'date_of_birth' => $dateOfBirth,
                'email' => $user['email'],
                'account' => $user['account']
            ]);

            $userModel->save();

            //Also store the import location from which we got the user info
            $userImport = UserImportLocation::create([
                'file_hash' => $this->filePath,
                'document_id' => $this->docNumber
            ]);

            $userImport->user()->associate($userModel);
            $userImport->save();
        }

        //Add creditcard and associate with user
        //Optionally add extra regex to filter on three successive identical numbers
        if (!isset($this->processedCardDocNumbers[$this->docNumber]) && isset($user['credit_card'])) {
            //If for example the user was already in the DB (so we didn't have to create it) we still need it if we
            //don't have a creditcard in the DB yet.
            if (!$userModel) {
                $userModel = UserImportLocation::where('file_hash', $this->path)->where('document_id', $this->docNumber)
                    ->get()->first()->user();
            }

            /** @var Creditcard $card */
            $cardModel = Creditcard::create([
                'type' => $user['credit_card']['type'],
                'number' => $user['credit_card']['number'],
                'name' => $user['credit_card']['name'],
                'expiration_date' => Carbon::parse($user['credit_card']['expirationDate'])
            ]);

            $cardModel->user()->associate($userModel);
            $cardModel->save();

            //Also store the import location from which we got the creditcard info
            $cardImport = CreditcardImportLocation::create([
                'file_hash' => $this->fileHash,
                'document_id' => $this->docNumber
            ]);

            $cardImport->creditcard()->associate($cardModel);
            $cardImport->save();
        }
    }
}
