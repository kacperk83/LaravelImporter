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

    private $processedDocNumbersWithUsers;
    private $processedDocNumbersWithCards;
    private $filePath;
    private $docNumber;

    /**
     * UserImportClosure constructor.
     *
     * @param $processedDocNumbersWithUsers
     * @param $processedDocNumbersWithCards
     * @param $filePath
     * @param $docNumber
     */
    public function __construct($processedDocNumbersWithUsers, $processedDocNumbersWithCards, $filePath, &$docNumber)
    {
        $this->processedDocNumbersWithUsers = $processedDocNumbersWithUsers;
        $this->processedDocNumbersWithCards = $processedDocNumbersWithCards;
        $this->filePath = $filePath;
        $this->docNumber = $docNumber;
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

        $this->docNumber++;

        try {
            $dateOfBirth = Carbon::parse($user['date_of_birth']);
            $age = $dateOfBirth->diffInYears(Carbon::now());
        } catch (\Exception $e) {
            $dateOfBirth = null;
            $age = null;
        }

        //Add the user
        if (!isset($this->processedDocNumbersWithUsers[$this->docNumber]) &&
            (is_null($user['date_of_birth']) || is_null($age) || ($age > 18 && $age < 65))
        ) {
            $userModel = User::create([
                'name' => $user['name'],
                'address' => $user['address'],
                'checked' => $user['checked'],
                'description' => $user['description'],
                'interest' => $user['interest'],
                'date_of_birth' => $dateOfBirth,
                'email' => $user['email'],
                'account' => $user['account'],
                // 'linenumber' => $line,
                //  'imported_from' => $filename
            ]);

            $userModel->save();

            //Also store the import location from which we got the user info
            $userImport = UserImportLocation::create([
                'file_path' => $this->filePath,
                'document_id' => $this->docNumber
            ]);
            
            $userImport->user()->associate($userModel);
            $userImport->save();

        } elseif (isset($this->processedDocNumbersWithUsers[$this->docNumber])) {
            $userModel = $this->processedDocNumbersWithUsers[$this->docNumber];
        } else {
            return;
        }

        //Add creditcard and associate with user
        //Optionally add extra regex to filter on three successive identical numbers
        if (!isset($this->processedDocNumbersWithCards[$this->docNumber]) && isset($user['credit_card'])) {

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
                'file_path' => $this->filePath,
                'document_id' => $this->docNumber
            ]);

            $cardImport->creditcard()->associate($cardModel);
            $cardImport->save();
        }
    }
}
