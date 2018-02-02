<?php

namespace App\Http\Responses;

/**
 * Class UserResponse
 *
 * @package App\Http\Responses
 *
 * @author  Kacper Kowalski kacperk83@gmail.com
 */
class UserResponse extends BaseResponse
{
    /**
     * @var array $mapping
     */
    protected $mapping = [
        'id' => 'id',
        'name' => 'name',
        'address' => 'address',
        'interest' => 'interest',
        'date_of_birth' => 'date_of_birth',
        'email' => 'email',
        'account' => 'account',
        'creditcards' => 'creditcards'
    ];
}
